<?php
declare(strict_types=1);


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Translator\Translator as t;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Class Middlewares
 * @package GuzabaPlatform\Platform\Application
 * A service that provides access to inject middlewares by the components
 */
class Middlewares extends \Guzaba2\Http\Middlewares
{

}