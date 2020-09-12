<?php

declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;


use Azonmedia\Routing\Interfaces\RouterInterface;
use Guzaba2\Base\Interfaces\ConfigInterface;
use Guzaba2\Base\Interfaces\ObjectInternalIdInterface;
use Guzaba2\Base\Interfaces\UsesServicesInterface;
use Guzaba2\Base\Traits\SupportsConfig;
use Guzaba2\Base\Traits\SupportsObjectInternalId;
use Guzaba2\Base\Traits\UsesServices;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Swoole\Server;
use Guzaba2\Translator\Translator as t;

class RoutesMap extends \Azonmedia\RoutesMap\RoutesMap implements ConfigInterface, ObjectInternalIdInterface, UsesServicesInterface
{

    use SupportsConfig;
    use SupportsObjectInternalId;
    use UsesServices;

    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'Events',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * VueRouter constructor.
     * @overrides
     * @param string $router_file
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     */
    public function __construct(RouterInterface $Router, string $router_file)
    {
        parent::__construct($Router, $router_file);
        self::get_service('Events')->add_class_callback(Server::class, '_before_start', [$this, 'dump_routes_map']);
    }

    /**
     * @overrides
     * Dumps the routes to the $router_file provided in the constructor
     */
    public function dump_routes_map() : void
    {
        parent::dump_routes_map();
        Kernel::printk(sprintf(t::_('%1$s: Javascript routes map dumped to %2$s'), __CLASS__, realpath($this->get_routes_map_file()) ).PHP_EOL);
    }
}