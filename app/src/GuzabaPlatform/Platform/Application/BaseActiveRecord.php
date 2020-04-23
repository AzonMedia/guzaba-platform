<?php
declare(strict_types=1);


namespace GuzabaPlatform\Platform\Application;

//use Guzaba2\Orm\ActiveRecordWithAuthorization;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Orm\ActiveRecord;
use GuzabaPlatform\Platform\Application\Interfaces\ModelInterface;
use Guzaba2\Translator\Translator as t;

//abstract class BaseActiveRecord extends ActiveRecordWithAuthorization
abstract class BaseActiveRecord extends ActiveRecord implements ModelInterface
{

    public function get_object_name() : string
    {
        $object_name_property = static::get_object_name_property();
        return $this->{$object_name_property};
    }

    public static function get_object_name_property(): string
    {
        if (!isset(static::CONFIG_RUNTIME['object_name_property'])) {
            throw new RunTimeException(sprintf(t::_('The class %1$s is missing the CONFIG_DEFAULTS[\'object_name_property\'] configuation option.'), static::class));
        }
        if (!in_array(static::CONFIG_RUNTIME['object_name_property'], static::get_property_names())) {
            throw new RunTimeException(sprintf(t::_('The %1$s::CONFIG_DEFAULTS[\'object_name_property\'] config options contains an invalid property name %2$s. The class %3$s does have such a property.'), static::class, static::CONFIG_RUNTIME['object_name_property'], static::class ));
        }
        return static::CONFIG_RUNTIME['object_name_property'];
    }
}