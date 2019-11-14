<?php
namespace GuzabaPlatform\bin;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;

require_once('Start.php');

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
            'description'       => 'Add default path to project',
            'default'           => NULL,
            'option'            => ['swoole' => ['server_options' => ['document_root' => NULL ] ] ],
        ],
        'enable-ssl'                    => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Enable SSL (certificates must be at ../certificates/localhost.crt and ../certificates/localhost.key)',
            'default'           => NULL,
            'option'            => 'enable_ssl',
        ],
        'enable-http2'                  => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Enable HTTP2 (document-root is not supported in this mode)',
            'default'           => NULL,
            'option'            => 'enable_http2',
        ],
        'cors-origin'                   => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Add Cross-Origin Resource Sharing',
            'default'           => NULL,
            'option'            => 'cors_origin',
        ],
        'log-level'                     => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_REQUIRED,
            'description'       => 'Sets the log level as per the PSR-3',
            'default'           => NULL,
            'option'            => 'log_level',
        ],
        'disable-all-class-load'        => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Do not load all classes on startup',
            'default'           => NULL,
            'option'            => ['kernel' => ['disable_all_class_load' => NULL]],
        ],
        'disable-all-class-validation'  => [
            'shortcut'          => NULL,
            'input'             => InputOption::VALUE_NONE,
            'description'       => 'Do not validate all loaded classes on startup',
            'default'           => NULL,
            'option'            => ['kernel' => ['disable_all_class_validation' => NULL]],
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


        $cli_options_mapping = [GuzabaPlatform::class => (array) $command->get_parsed_options()];


        // this will print out the Symfony Console result from commands (help, list, ... ), error messages, ...
        $cli_output_errors = $output->fetch();
        if ($cli_output_errors) {
            print $cli_output_errors;
            exit(1);
        }

        return $cli_options_mapping;
    }
}


