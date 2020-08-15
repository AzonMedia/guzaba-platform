<?php

declare(strict_types=1);

namespace GuzabaPlatform\bin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Start
 * @package GuzabaPlatform\bin
 */
class Start extends Command
{

    protected array $parsed_options = [];

    /**
     * Initializes the options by reading CliOptions::CLI_OPTIONS
     */
    public function configure() : void
    {
        $this->setName('start')->setDescription('Sets application options.')->setHelp('Sets application options.');
        foreach (CliOptions::CLI_OPTIONS as $option_name => $option_settings) {
            $this->addOption($option_name, $option_settings['shortcut'], $option_settings['input'], $option_settings['description'], $option_settings['default']);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output) : int
    {

        foreach (CliOptions::CLI_OPTIONS as $option_name => $option_settings) {
            if ($input->getOption($option_name)) {
                //self::assign_value($this->options, $option_settings['option'], $input->getOption($option_name));
                $pointer =& $this->parsed_options[$option_settings['class']];
                self::assign_value($pointer, $option_settings['option'], $input->getOption($option_name));
            }
        }

        return 0;
    }

    /**
     * Returns an associative array with the parsed options.
     * @return array
     */
    public function get_parsed_options() : array
    {
        return $this->parsed_options;
    }

    /**
     * @param mixed $options
     * @param mixed $option_name
     * @param mixed $option_value
     */
    private static function assign_value( /* mixed */ &$options, /* mixed */ $option_name, /* mixed */ $option_value) : void
    {

        if (is_string($option_name)) {
            $options[$option_name] = $option_value;
        } elseif (is_array($option_name)) {
            if (count($option_name) > 1) {
                throw new \InvalidArgumentException(sprintf(t::_('The array provided for $option_name can contain only one entry.')));
            }
            $key = array_key_first($option_name);
            if ($options === NULL) {
                $options = [];
            }
            if (!array_key_exists($key, $options)) {
                $options[$key] = [];
            }

            $pointer =& $options[$key];
            $option_name = $option_name[$key];
            if ($option_name === NULL) {
                $pointer = $option_value;
            } elseif (is_array($option_name)) {
                self::assign_value($pointer, $option_name, $option_value);
            } else {
                $pointer = $option_name;//the option_name (the next level in the array nesting) is the actual value
            }

        } else {
            throw new \InvalidArgumentException(sprintf('An unsupported type %s is provided for $option_name', gettype($option_name) ));
        }
    }
}
