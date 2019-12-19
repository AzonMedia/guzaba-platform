<?php

namespace GuzabaPlatform\Platform\Application\Traits;

trait ConnectionConstructorTrait
{
    public function __construct()
    {
        $options = array_filter(self::CONFIG_RUNTIME, fn(string $key) : bool => in_array($key, static::get_supported_options()), ARRAY_FILTER_USE_KEY );
        parent::__construct($options);
    }
}