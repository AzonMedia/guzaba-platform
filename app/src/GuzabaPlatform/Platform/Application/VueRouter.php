<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;


use Azonmedia\Utilities\AlphaNumUtil;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Base\Interfaces\ConfigInterface;
use Guzaba2\Base\Interfaces\ObjectInternalIdInterface;
use Guzaba2\Base\Interfaces\UsesServicesInterface;
use Guzaba2\Base\Traits\SupportsConfig;
use Guzaba2\Base\Traits\SupportsObjectInternalId;
use Guzaba2\Base\Traits\UsesServices;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Swoole\Server;
use Guzaba2\Translator\Translator as t;

/**
 * Class VueRouter
 * @package GuzabaPlatform\Platform\Application
 * Used as a service by the components that need to export routes.
 */
class VueRouter extends \Azonmedia\VueRouter\VueRouter implements ConfigInterface, ObjectInternalIdInterface, UsesServicesInterface
{

    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'Events',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    use SupportsConfig;
    use SupportsObjectInternalId;
    use UsesServices;

    /**
     * VueRouter constructor.
     * @overrides
     * @param string $router_file
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     */
    public function __construct(string $router_file)
    {
        parent::__construct($router_file);
        self::get_service('Events')->add_class_callback(Server::class, '_before_start', [$this, 'dump_routes']);
    }

    /**
     * @overrides
     * Dumps the routes to the $router_file provided in the constructor
     */
    public function dump_routes() : void
    {
        $this->set_routes_dumped(TRUE);
        $routes_str = $this->as_string();
        Kernel::file_put_contents($this->get_router_file(), $routes_str);//replace the old file
    }
}