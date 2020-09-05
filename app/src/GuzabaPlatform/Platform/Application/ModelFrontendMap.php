<?php

declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;


use Azonmedia\Utilities\FilesUtil;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Interfaces\ConfigInterface;
use Guzaba2\Base\Interfaces\ObjectInternalIdInterface;
use Guzaba2\Base\Interfaces\UsesServicesInterface;
use Guzaba2\Base\Traits\SupportsConfig;
use Guzaba2\Base\Traits\SupportsObjectInternalId;
use Guzaba2\Base\Traits\UsesServices;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Orm\Interfaces\ActiveRecordInterface;
use Guzaba2\Swoole\Server;
use Guzaba2\Translator\Translator as t;

class ModelFrontendMap extends \Azonmedia\ModelFrontendMap\ModelFrontendMap implements ConfigInterface, ObjectInternalIdInterface, UsesServicesInterface
{
    protected const CONFIG_DEFAULTS = [
        'services' => [
            'FrontendHooks',
            'Events',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    use SupportsConfig;
    use SupportsObjectInternalId;
    use UsesServices;

    public function __construct(string $frontend_view_map_file, string $frontend_manage_map_file)
    {
        parent::__construct($frontend_view_map_file, $frontend_manage_map_file);
        self::get_service('Events')->add_class_callback(Server::class, '_before_start', [$this, 'dump_view_map']);
    }

    /**
     * @overrides
     * @param string $model_class
     * @param string $frontend_component
     * @throws InvalidArgumentException
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     */
    public function add_view_mapping(string $model_class, string $frontend_component): void
    {
        if (!$model_class) {
            throw new InvalidArgumentException(sprintf(t::_('No model_class provided.')));
        }
        if (!class_exists($model_class)) {
            throw new InvalidArgumentException(sprintf(t::_('No class %1$s exists.'), $model_class));
        }
        if (!is_a($model_class, ActiveRecordInterface::class, true)) {
            throw new InvalidArgumentException(sprintf(t::_('The provided model class %1$s is not an %2$s.'), $model_class, ActiveRecordInterface::class));
        }
        if (!$frontend_component) {
            throw new InvalidArgumentException(sprintf(t::_('There is not frontend_component provided.')));
        }
        /** @var VueComponentHooks $FrontendHooks */
        $FrontendHooks = self::get_service('FrontendHooks');
        if (!$FrontendHooks->component_file_exists($frontend_component, $file_error)) {
            throw new InvalidArgumentException($file_error);
        }
        //$this->view_map[$model_class] = $frontend_component;
        parent::add_view_mapping($model_class, $frontend_component);
    }

    public function dump_view_map(): void
    {
        parent::dump_view_map();
        Kernel::printk(sprintf(t::_('%1$s: PHP Model to Vue view frontend component map dumped to %2$s'), __CLASS__, realpath($this->get_view_map_file()) ).PHP_EOL);
    }
}