<?php
require_once('Start.php');

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\BufferedOutput;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;

function get_cli_options() : array
{
	$command = new Guzaba2\Platform\Start();
	$output = new BufferedOutput();

	$app = new Application();
	// register the command
	$app -> add($command);
	$app -> setAutoExit(FALSE);
	// call start command without typing it in the console
	$app -> setDefaultCommand($command->getName());
	$app -> run(NULL, $output);

	if (isset($output->options)) {
		$cli_options_mapping = [GuzabaPlatform::class => (array) $output->options];
	} else {
		$cli_options_mapping = [];
	}

	print_r($output->fetch());

	return $cli_options_mapping;
}
