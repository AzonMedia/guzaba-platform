<?php
declare(strict_types=1);
namespace GuzabaPlatform\bin;

use Guzaba2\Authorization\Acl\AclCreateAuthorizationProvider;
use Guzaba2\Di\Container;
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
            'description'       => 'Enable HTTP2 (requires also --enable-ssl to be provided)',
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
        'disable-validations'           => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Do not run the validations at startup.',
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
        'enable-debug-ports'            => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Enables the debug ports for the Swoole Workers.',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => ['swoole' => ['enable_debug_ports' => NULL]],
        ],
        'target-language'               => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Sets the target language for the translations. Valid options are "en", "bg" or other ISO 639 code (ver 1,2,3). Set this option to have the startup messages translated.',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'target_language',
        ],
        'skip-translator'               => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Prevents the translator initialization. Use when no translations are needed (the application language matches the source language).',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'skip_translator',
        ],
        'disable-class-cache'            => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Disables class cache. By default the cache is enabled and checks the mtime of the registry files and the mtime of the original file and only if the mtime of the cached file if more recent the cached file is used.',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'disable_class_cache',
        ],
        'no-permission-checks'          => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Sets the AuthorizationProvider service in the DI container to AclCreateAuthorizationProvider. This allows permission management but does not check the permissions. To be used in development.',
            'default'           => NULL,
            'class'             => Container::class,
            'option'            => ['dependencies' => ['AuthorizationProvider' => ['class' => AclCreateAuthorizationProvider::class] ] ],
        ],
        'allow-no-permission-checks-in-production'      => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Allows a non enforcing AuthorizationProvider to be used in production. See the --no-permission-checks and --deployment options.',
            'default'           => NULL,
            'class'             => GuzabaPlatform::class,
            'option'            => 'allow_no_permission_checks_in_production',
        ]
    ];

    /**
     * @return array
     * @throws \Exception
     */
    public static function get_cli_options() : array
    {
        $command = new Start();
        $output = new BufferedOutput();

        $app = new Application();
        // register the command
        $app->add($command);
        $app->setAutoExit(FALSE);
        // call start command without typing it in the console
        $app->setDefaultCommand($command->getName());
        $app->run(NULL, $output);

        $cli_options_mapping = $command->get_parsed_options();

        // this will print out the Symfony Console result from commands (help, list, ... ), error messages, ...
        $cli_output_errors = $output->fetch();
        if ($cli_output_errors) {
            print $cli_output_errors;
            exit(1);//if there is an unsupported argument provided exit and show the supported ones
        }

        return $cli_options_mapping;
    }
}


