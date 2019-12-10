<?php
declare(strict_types=1);
namespace GuzabaPlatform\bin;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;

/**
 * Class CliOptions
 * @package GuzabaPlatform\bin
 * This class contains the CLI options mapping to the configuration options of GuzabaPlatform.
 * These options can override only config options of GuzabaPlatform\Platform\Application\GuzabaPlatform class.
 */
abstract class CliOptions
{
    public const CLI_OPTIONS = [
        'document-root'                 => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Sets the DocumentRoot for the static serving (static_handler is enabled by default)',
            'default'           => NULL,//is the actual value of GuzabaPlatform::CONFIG_DEFAULTS['some_prop'] but if this is invoked will actually load the class. This class must be loaded with Kernel::autoloader()
            'class'             => GuzabaPlatform::class,
            'option'            => ['swoole' => ['server_options' => ['document_root' => NULL ] ] ],
        ],
        'disable-static-handler'         => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Disables the handler of static content',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'disable_static_handler',
        ],
        'enable-ssl'                    => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Enable SSL (certificates must be available at ../certificates/localhost.crt and ../certificates/localhost.key)',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'enable_ssl',
        ],
        'ssl-cert-file'                 => [
            'shortcut'          => NULL,
            //'input'             => InputOption::VALUE_OPTIONAL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Sets ssl cert file',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => ['swoole' => ['server_options' => ['ssl_cert_file' => NULL ] ] ],
        ],
        'ssl-key-file'                 => [
            'shortcut'          => NULL,
            //'input'             => InputOption::VALUE_OPTIONAL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Sets ssl key file',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => ['swoole' => ['server_options' => ['ssl_key_file' => NULL ] ] ],
        ],
        'enable-http2'                  => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Enable HTTP2 (requires also --disable-static-handler --enable-ssl to be provided)',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'enable_http2',
        ],
        'cors-origin'                   => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Add Cross-Origin Resource Sharing',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'cors_origin',
        ],
        'log-level'                     => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Sets the log level. Valid options are "debug", "info", "notice", "warning", "error", "critical", "alert", "emergency". Default is "debug".',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'log_level',
        ],
//it is really bad idea to allow this
//        'disable-all-class-load'        => [
//            'shortcut'          => NULL,
//            'input'             => InputOption::VALUE_NONE,
//            'description'       => 'Do not load all classes on startup',
//            'default'           => NULL,
//            'class'             => GuzabaPlatform::class,
//            'option'            => ['kernel' => ['disable_all_class_load' => NULL]],
//        ],
        'disable-all-class-validation'  => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Do not validate all loaded classes on startup',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => ['kernel' => ['disable_all_class_validation' => NULL]],
        ],
        'deployment'                    => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Valid options are "production", "staging", "deployment". Default is "deployment". In "production" Swoole is daemonized.',
            'default'           => NULL,
            'class'             => \Guzaba2\Application\Application::class,
            'option'            => 'deployment',
        ],

    ];

    public static function get_cli_options() : array
    {
        $command = new Start();
        $output = new BufferedOutput();

        $app = new Application();
        // register the command
        $app -> add($command);
        $app -> setAutoExit(FALSE);
        // call start command without typing it in the console
        $app -> setDefaultCommand($command->getName());
        $app -> run(NULL, $output);

        $cli_options_mapping = $command->get_parsed_options();

        // this will print out the Symfony Console result from commands (help, list, ... ), error messages, ...
        $cli_output_errors = $output->fetch();
        if ($cli_output_errors) {
            print $cli_output_errors;
            exit(1);
        }

        return $cli_options_mapping;
    }
}


