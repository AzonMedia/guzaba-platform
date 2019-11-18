<?php
declare(strict_types=1);

namespace GuzabaPlatform\bin;

use Azonmedia\Registry\RegistryBackendArray;
use Guzaba2\Di\Container;
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

    error_reporting(E_ALL);

    if (version_compare(phpversion(),'7.4.0RC5', '<')) {
        print 'The application requires PHP 7.4.0RC5 or higher.'.PHP_EOL;
        exit(1);
    }

    $autoload_path = realpath(__DIR__ . '/../../vendor/autoload.php');
    require_once($autoload_path);

    require_once('CliOptions.php');
    require_once('Start.php');
    $cli_options_mapping = CliOptions::get_cli_options();

    //ini_set("swoole.enable_preemptive_scheduler","1");
    //\Swoole\Coroutine::set([ 'enable_preemptive_scheduler' => 1 ]);
    //the above is available in Master branch only not released yet

    $log_level = LogLevel::DEBUG;//default to Debug
    if (isset($cli_options_mapping['GuzabaPlatform\\Platform\\Application\\GuzabaPlatform']['log_level'])) {
        $log_level = $cli_options_mapping['GuzabaPlatform\\Platform\\Application\\GuzabaPlatform']['log_level'];
    }

    $initial_directory = getcwd();
    $app_directory = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    $generated_files_dir = $app_directory.'startup_generated';
    $generated_runtime_config_dir  = $generated_files_dir.'/runtime_configs';
    $generated_runtime_config_file = $generated_files_dir.'/runtime_config.php';

    chdir($app_directory);

//    $RegistryBackendEnv = new RegistryBackendEnv('');
//    $Registry = new Registry($RegistryBackendEnv, $generated_runtime_config_file, $generated_runtime_config_dir);
//
//    $RegistryBackendArray = new RegistryBackendArray(realpath(__DIR__ . '/../registry'));
//    $Registry->add_backend($RegistryBackendArray);
//
//    $RegistryBackendCli = new RegistryBackendCli($cli_options_mapping);
//    $Registry->add_backend($RegistryBackendCli);

    //Registry Setup
    //the priority from highest to lowest is: Cli, Env, Array
    $RegistryBackendCli = new RegistryBackendCli($cli_options_mapping);
    $Registry = new Registry($RegistryBackendCli, $generated_runtime_config_file, $generated_runtime_config_dir);
    $RegistryBackendEnv = new RegistryBackendEnv('');
    $Registry->add_backend($RegistryBackendEnv);
    $RegistryBackendArray = new RegistryBackendArray(realpath(__DIR__ . '/../registry'));
    $Registry->add_backend($RegistryBackendArray);



    $Logger = new Logger('main_log');
    $Formatter = new LineFormatter(
        NULL, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
        NULL, // Datetime format
        TRUE, // allowInlineLineBreaks option, default false
        TRUE  // ignoreEmptyContextAndExtra option, default false
    );

    $FileHandler = new StreamHandler($app_directory.'logs'.DIRECTORY_SEPARATOR.'main_log.txt', $log_level);
    $FileHandler->setFormatter($Formatter);
    $Logger->pushHandler($FileHandler);

    $StdoutHandler = new StreamHandler('php://stdout', $log_level);
    $StdoutHandler->setFormatter($Formatter);
    $Logger->pushHandler($StdoutHandler);

    Kernel::initialize($Registry, $Logger);

    $DependencyContainer = new Container();
    Kernel::set_di_container($DependencyContainer);

    //from this point the kernel (and most importantly the autoloader) is usable
    //up until this point no Guzaba2 classes can be autoloaded (only composer autoload works - from other packages)

    $app_class_path = realpath($app_directory.'src'.DIRECTORY_SEPARATOR);

    //registers where this application classes are located
    Kernel::register_autoloader_path('GuzabaPlatform\\Platform', $app_class_path);

    //past this point it is possible to autoload Application specific classes

    new GuzabaPlatform($app_directory, $generated_files_dir);

    chdir($initial_directory);
})();
