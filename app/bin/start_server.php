<?php
declare(strict_types=1);

namespace GuzabaPlatform\bin;

use Azonmedia\Packages\Packages;
use Azonmedia\Registry\RegistryBackendArray;
use Azonmedia\Translator\Translator;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Di\Container;
use Guzaba2\Kernel\SourceStream;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;
use Azonmedia\Registry\Registry;
use Azonmedia\Registry\RegistryBackendCli;
use Azonmedia\Registry\RegistryBackendEnv;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Registry\Interfaces\RegistryInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;

(function() {

    $init_microtime = microtime(TRUE);//needed by the kernel

    error_reporting(E_ALL);

    $php_min_version_required = '7.4.0';
    if (version_compare(phpversion(), $php_min_version_required, '<')) {
        print sprintf('The application requires PHP %s or higher.', $php_min_version_required).PHP_EOL;
        exit(1);
    }
    //$autoload_path = realpath(__DIR__ . '/../../../../autoload.php');//is symlink is used
    $autoload_path = realpath(__DIR__ . '/../../vendor/autoload.php');//if copy is done on installation
    require_once($autoload_path);

    require_once('CliOptions.php');
    require_once('Start.php');
    $cli_options_mapping = CliOptions::get_cli_options();

    Kernel::set_init_microtime($init_microtime);
    Kernel::printk(sprintf('Run start_server --help for startup options') . PHP_EOL);

    $initial_directory = getcwd();
    $app_directory = realpath(dirname($autoload_path) .'/../app/');

    // ===== begin Translator initialization ===== //
    $skip_translator = FALSE;
    if (isset($cli_options_mapping['GuzabaPlatform\\Platform\\Application\\GuzabaPlatform']['skip_translator'])) {
        $skip_translator = TRUE;
    }
    //temporary fix - skip translator if SAPI === apache || cgi-fcgi
    $sapi = kernel::get_php_sapi_name();
    if (in_array($sapi, [Kernel::SAPI['APACHE'], Kernel::SAPI['CGI']])) {
        $skip_translator = TRUE;
    }

    $target_language = 'en';//the target language can be changed at any time during execution. It needs to be set for each request (coroutine) served (based on route or accept headers).
    //TODO - retrieve the target language from the translation.json
    if (isset($cli_options_mapping['GuzabaPlatform\\Platform\\Application\\GuzabaPlatform']['target_language'])) {
        if ($skip_translator) {
            print sprintf('The --skip-translator and --target-language options are incompatible.').PHP_EOL;
            exit(1);
        }
        $target_language = $cli_options_mapping['GuzabaPlatform\\Platform\\Application\\GuzabaPlatform']['target_language'];
    }

    if (!$skip_translator) {
        Kernel::printk(sprintf('Initializing Translator') . PHP_EOL);
        Kernel::printk(sprintf('Current target language is "%s", use --target-language to change it', $target_language) . PHP_EOL);
        $composer_file_path = Packages::get_application_composer_file_path();
        $packages_filter = ['/azonmedia.*/i', '/guzaba-platform.*/i', '/guzaba.*/i'];
        $additional_paths = [];
        $app_translations_directory = $app_directory.'/src/translations/';
        if (file_exists($app_translations_directory) && is_dir($app_translations_directory)) {
            $additional_paths[] = $app_translations_directory;
        }
        Translator::initialize($target_language, $composer_file_path, $packages_filter, $additional_paths);
        Kernel::printk(sprintf(Translator::_('Loaded %s translations from %s packages'), Translator::get_loaded_messages_count(), count(Translator::get_loaded_packages())) . PHP_EOL);
    } else {
        Kernel::printk(sprintf('Translator initialization is prevented by configuration') . PHP_EOL);
    }

    // ===== end Translator initialization ===== //

    //ini_set("swoole.enable_preemptive_scheduler","1");
    //\Swoole\Coroutine::set([ 'enable_preemptive_scheduler' => 1 ]);
    //do not enable the above until it is guaranteed that there is no shared/changed global state

    $log_level = LogLevel::DEBUG;//default to Debug
    if (isset($cli_options_mapping['GuzabaPlatform\\Platform\\Application\\GuzabaPlatform']['log_level'])) {
        $log_level = $cli_options_mapping['GuzabaPlatform\\Platform\\Application\\GuzabaPlatform']['log_level'];
    }
    if (isset($cli_options_mapping['Guzaba2\\Application\\Application']['deployment']) && strtolower($cli_options_mapping['Guzaba2\\Application\\Application']['deployment']) === 'production') {
        if (in_array(strtolower($log_level), ['debug', 'info'])) {
            //in production debug and info should not be used as this will fill the logs - raise the level to the minimum notice
            $log_level = 'notice';
        }
    }


    $generated_files_dir = $app_directory.'/startup_generated';
    $generated_runtime_config_dir  = $generated_files_dir.'/runtime_configs';
    $generated_runtime_config_file = $generated_files_dir.'/runtime_config.php';

    chdir($app_directory);

    $class_cache_disabled = FALSE;
    //log the last start line as it may contain registry overrides
    //and as there are cached classes these will not be regenerated if the command line is different
    $last_cli_path = $generated_files_dir.'/last_startup_args';
    $last_command_args = [];
    if (file_exists($last_cli_path)) {
        $last_command_args = file_get_contents($last_cli_path);
    }
    $current_command_args = $_SERVER['argv'];
    file_put_contents($last_cli_path, print_r($current_command_args, TRUE));//update the last command
    if (print_r($current_command_args, TRUE) !== $last_command_args) { //$current_command_args is array , $last_command_args is string
        $class_cache_disabled = TRUE;//ignore the existing class cache and regenerate - like a modification in the registry
    }
    if (isset($cli_options_mapping['GuzabaPlatform\\Platform\\Application\\GuzabaPlatform']['disable_class_cache'])) {
        $class_cache_disabled = TRUE;
    }

    //Registry Setup
    //the priority from highest to lowest is: Cli, Env, Array. Cli args override env vars, and env vars override php array config.
    //the fallback is registered first and then in increasing priority

    $registry_dir = $app_directory . '/registry';
    if (in_array($sapi, [Kernel::SAPI['APACHE'], Kernel::SAPI['CGI']])) {
        $registry_dir = $app_directory . '/registry_httpd';
    }

    $RegistryBackendArray = new RegistryBackendArray(realpath($registry_dir));
    $Registry = new Registry($RegistryBackendArray, $generated_runtime_config_file, $generated_runtime_config_dir);

    $RegistryBackendEnv = new RegistryBackendEnv('');
    $Registry->add_backend($RegistryBackendEnv);

    $RegistryBackendCli = new RegistryBackendCli($cli_options_mapping);
    $Registry->add_backend($RegistryBackendCli);


    $Logger = new Logger('main_log');
    $Formatter = new LineFormatter(
        NULL, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
        NULL, // Datetime format
        TRUE, // allowInlineLineBreaks option, default false
        TRUE  // ignoreEmptyContextAndExtra option, default false
    );

    //use the below IF to disable the normal log in production node if needed (startup messages will not be logged)
    //if (!isset($cli_options_mapping['Guzaba2\\Application\\Application']['deployment']) || strtolower($cli_options_mapping['Guzaba2\\Application\\Application']['deployment']) !== 'production') {
    $log_file = $app_directory.'/logs'.DIRECTORY_SEPARATOR.'main_log.txt';
    $FileHandler = new StreamHandler($log_file, $log_level);
    $FileHandler->setFormatter($Formatter);
    $Logger->pushHandler($FileHandler);
    //}


    $StdoutHandler = new StreamHandler('php://stdout', $log_level);
    $StdoutHandler->setFormatter($Formatter);
    $Logger->pushHandler($StdoutHandler);


    $options = [
        SourceStream::class => [ //these will be passed to the SourceStream class
            'class_cache_enabled'   => !$class_cache_disabled,
            'class_cache_dir'       => $app_directory.'/startup_generated/classes',
            'registry_dir'          => $registry_dir,//if provided it will compare the mtime of all the registry files and the cached classes
        ],
    ];
    Kernel::initialize($Registry, $Logger, $options);

    $DependencyContainer = new Container();
    Kernel::set_di_container($DependencyContainer);

    //from this point the kernel (and most importantly the autoloader) is usable
    //up until this point no Guzaba2 classes can be autoloaded (only composer autoload works - from other packages)

    $root_directory = realpath($app_directory.'/../');
    $manifest_json_file = $root_directory.'/manifest.json';
    if (!file_exists($manifest_json_file) || !is_readable($manifest_json_file)) {
        throw new RunTimeException(sprintf('The file %s does not exist. This file is created when "composer require guzaba-platofrm/guzaba-platform" is executed.', $manifest_json_file));
    }
    $Manifest = json_decode(file_get_contents($manifest_json_file));
    foreach ($Manifest->components as $Component) {
        //$ns_as_path = str_replace('\\','/',$Component->namespace);
        $src_dir = $Component->src_dir;
        Kernel::register_autoloader_path($Component->namespace, $src_dir);
    }
    //use the composer autoload path and provide it to the Kernel (the Kernel::autoloader() will be used, not the composer one even though this was defined in the composer.json file)
    $Composer = json_decode(file_get_contents($root_directory.'/composer.json'), TRUE);
    if (isset($Composer['autoload']['psr-4'])) {
        foreach ($Composer['autoload']['psr-4'] as $namespace=>$rel_path) {
            Kernel::register_autoloader_path($namespace, $root_directory.'/'.$rel_path);
        }
    };
    //if there is no autoload/psr-4 section in the composer.json file then here an explicit call to Kernel::register_autoloader_path() needs to be done and the namespace prefix and path provided.

    //past this point it is possible to autoload Application specific classes

    new GuzabaPlatform($app_directory, $generated_files_dir);

    chdir($initial_directory);
})();
