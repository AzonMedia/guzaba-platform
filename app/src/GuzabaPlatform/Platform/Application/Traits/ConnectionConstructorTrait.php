<?php

namespace GuzabaPlatform\Platform\Application\Traits;

use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Translator\Translator as t;

trait ConnectionConstructorTrait
{
    public function __construct()
    {
        $options = array_filter(self::CONFIG_RUNTIME, fn(string $key) : bool => in_array($key, static::get_supported_options()), ARRAY_FILTER_USE_KEY );
        if ($options['host'] === 'hostname.or.ip') {
            throw new RunTimeException(sprintf(t::_('It appears that the database connection is not configured. You may try to copy ./registry/local.php.dist to ./registry/local.php and set the needed values there. Or if you are using ./app/bin/start_in_containers or start_containers check ./dockerfiles/GuzabaPlatform/guzaba-platform.env.')), 0 , NULL, 'fb0a71fb-fdf2-4c7e-954a-0208464334b5 ');
        }
        parent::__construct($options);
    }
}