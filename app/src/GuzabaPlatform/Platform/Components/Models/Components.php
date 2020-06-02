<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Components\Models;


use Guzaba2\Base\Base;
use Guzaba2\Translator\Translator as t;

class Components extends Base
{

    /**
     * Returns installed stores
     * @return Store[]
     */
    public static function get_stores(): array
    {

    }

    public static function add_store(Store $Store): void
    {

    }

    public static function remove_store(Store $Store): void
    {

    }

    /**
     * Returns all installed components from all stores
     * @return array
     */
    public static function get_installed_components(): array
    {

    }

    public static function get_available_components(): array
    {

    }

    /**
     * Returns the components that are available for installation.
     * Available components - installed components
     * @return array
     */
    public static function get_installable_components(): array
    {

    }
}