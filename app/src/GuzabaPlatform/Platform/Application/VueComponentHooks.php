<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

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
 * Class VueComponentHooks
 * @package GuzabaPlatform\Platform\Application
 */
class VueComponentHooks extends \Azonmedia\VueComponentHooks\VueComponentHooks implements ConfigInterface, ObjectInternalIdInterface, UsesServicesInterface
{

    protected const CONFIG_DEFAULTS = [
        'services' => [
            'Events',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    use SupportsConfig;
    use SupportsObjectInternalId;
    use UsesServices;


    public function __construct(string $component_hooks_dir, string $aliases_file)
    {
        parent::__construct($component_hooks_dir, $aliases_file);
        self::get_service('Events')->add_class_callback(Server::class, '_before_start', [$this, 'dump_hooks']);
    }

    public function dump_hooks(): void
    {
        parent::dump_hooks();
        Kernel::printk(sprintf(t::_('%1$s: Vue hooks dumped to %2$s'), __CLASS__, realpath($this->get_component_hooks_dir()) ).PHP_EOL);
    }
}