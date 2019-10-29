<?php
declare(strict_types=1);

namespace Guzaba2\Platform;

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

$autoload_path = realpath(__DIR__ . '/../../vendor/autoload.php');
require_once($autoload_path);

require_once('cli_options.php');
$cli_options_mapping = get_cli_options();

//https://github.com/swoole/swoole-docs/blob/master/get-started/examples/async_task.md
//https://www.swoole.co.uk/docs/modules/swoole-server/configuration
//these are default values
//but will be overriden by the env vars if they exist
//the env vars have GLOG_ prefix and are all caps
//GLOG_SWOOLE_HOST
/*
const APP_CONFIG = [
    'swoole_host'       => '0.0.0.0',
    'swoole_port'       => 8081,
    'worker_num'        => 4,//http workers
    'task_worker_num'   => 8,//tasks workers
    'data_dir'          => './data/',
    'log_dir'           => './logs/',
];
*/

(function() use ($cli_options_mapping){

    if (version_compare(phpversion(),'7.4.0RC4', '<')) {
        print 'The application requires PHP 7.4.0RC4 or higher.'.PHP_EOL;
        exit(1);
    }

    //ini_set("swoole.enable_preemptive_scheduler","1");
    //\Swoole\Coroutine::set([ 'enable_preemptive_scheduler' => 1 ]);
    //the above is available in Master branch only not released yet

    $log_level = LogLevel::INFO;

    $initial_directory = getcwd();
    $app_directory = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

    chdir($app_directory);

    $RegistryBackendCli = new RegistryBackendCli($cli_options_mapping);
    $Registry = new Registry($RegistryBackendCli);
    $RegistryBackendEnv = new RegistryBackendEnv('');
    $Registry->add_backend($RegistryBackendEnv);

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

    //from this point the kernel (and most importantly the autoloader) is usable
    //up until this point no Guzaba2 classes can be autoloaded (only composer autoload works - from other packages)

    $app_class_path = realpath($app_directory.'src'.DIRECTORY_SEPARATOR);

    //registers where this application classes are located
    Kernel::register_autoloader_path('GuzabaPlatform\\Platform', $app_class_path);

    //past this point it is possible to autoload Application specific classes

    new GuzabaPlatform($app_directory);

    chdir($initial_directory);
})();


