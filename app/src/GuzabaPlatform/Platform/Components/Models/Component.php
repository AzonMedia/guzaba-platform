<?php

namespace GuzabaPlatform\Platform\Components\Models;


use Guzaba2\Orm\ActiveRecord;

class Component extends ActiveRecord
{

    protected const CONFIG_DEFAULTS = [
        'main_table'            => 'components',
        'route'                 => '/component',
    ];

    protected const CONFIG_RUNTIME = [];

}