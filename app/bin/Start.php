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
            -> setDescription('Sets application options (by providing these to the Registry).')
            -> setHelp('Sets application options (by providing these to the Registry).')
            -> addOption ('document-root', NULL, InputOption::VALUE_REQUIRED, 'Add default path to project', NULL )
            -> addOption ('enable-ssl', NULL, InputOption::VALUE_NONE, 'Enable ssl', NULL )
            -> addOption ('enable-http2', NULL, InputOption::VALUE_NONE, 'Enable http2', NULL )
            -> addOption ('cors-origin', NULL, InputOption::VALUE_REQUIRED, 'Add Cross-Origin Resource Sharing', NULL )
            -> addOption ('log-level', NULL, InputOption::VALUE_REQUIRED, 'Sets the log level as per the PSR-3', NULL)
            ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('document-root')) {
            $output->options['swoole']['server_options']['document_root'] = $input->getOption('document-root');
        }

        if ($input->getOption('enable-ssl')) {
            $output->options['enable_ssl'] = TRUE;
        }

        if ($input->getOption('enable-http2')) {
            $output->options['enable_http2'] = TRUE;
        }

        if ($input->getOption('cors-origin')) {
            $output->options['cors_origin'] = $input->getOption('cors-origin');
        }

        if ($input->getOption('log-level')) {
            $output->options['log_level'] = $input->getOption('log-level');
        }

        return 0;
    }
}
