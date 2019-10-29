<?php 
namespace Guzaba2\Platform;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Start extends Command
{    
    public function configure()
    {
        $this 
            -> setName('start')
            -> setDescription('Sets ENV variables.')
            -> setHelp('Sets ENV variables.')
            -> addOption ('public', NULL, InputOption::VALUE_REQUIRED, 'Add default path to project', NULL )
            -> addOption ('enable-ssl', NULL, InputOption::VALUE_NONE, 'Enable ssl', NULL )
            -> addOption ('disable-ssl', NULL, InputOption::VALUE_NONE, 'Disable ssl', NULL )
            -> addOption ('enable-http2', NULL, InputOption::VALUE_NONE, 'Enable http2', NULL )
            -> addOption ('disable-http2', NULL, InputOption::VALUE_NONE, 'Disable http2', NULL )
            -> addOption ('cors-origin', NULL, InputOption::VALUE_REQUIRED, 'Add Cross-Origin Resource Sharing', NULL )
            -> addOption ('enable-static-handler', NULL, InputOption::VALUE_NONE, 'Enable static handler', NULL )
            -> addOption ('disable-static-handler', NULL, InputOption::VALUE_NONE, 'Disable static handler', NULL );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ( ($input->getOption('enable-ssl') && $input->getOption('disable-ssl')) ||
            ($input->getOption('enable-http2') && $input->getOption('disable-http2')) ||
            ($input->getOption('enable-ssl') && $input->getOption('disable-ssl'))) {
            die('Just one option may be used for enabling/disabling a setting!' . PHP_EOL);
        }

        print_r($input->getOptions());

        if ($input->getOption('public')) {
            $output->options['swoole']['server_options']['document_root'] = $input->getOption('public');
        }

        if ($input->getOption('enable-ssl')) {
            $output->options['enable_ssl'] = TRUE;
        }

        if ($input->getOption('disable-ssl')) {
            $output->options['enable_ssl'] = FALSE;
        }

        if ($input->getOption('enable-http2')) {
            $output->options['enable_http2'] = TRUE;
        }

        if ($input->getOption('disable-http2')) {
            $output->options['enable_http2'] = FALSE;
        }

        if ($input->getOption('enable-static-handler')) {
            $output->options['swoole']['server_options']['enable_static_handler'] = TRUE;
        }

        if ($input->getOption('disable-static-handler')) {
            $output->options['swoole']['server_options']['enable_static_handler'] = FALSE;
        }

        if ($input->getOption('cors-origin')) {
            $output->options['cors_origin'] = $input->getOption('cors-origin');
        }

        return 0;
    }
}
