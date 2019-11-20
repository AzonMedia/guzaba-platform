<?php

namespace GuzabaPlatform\Platform\Components\Models;


use Guzaba2\Orm\ActiveRecord;

class Component extends ActiveRecord
{

    protected const CONFIG_DEFAULTS = [
        'main_table'            => 'components',
        'route'                 => '/component',
        'structure' => [
            [
                'name' => 'object_uuid',
                'native_type' => 'varchar',
                'php_type' => 'string',
                'size' => 1,
                'nullable' => false,
                'column_id' => 1,
                'primary' => true,
                'autoincrement' => false,
                'default_value' => 0,
            ],
            [
                'name' => 'component_name',
                'native_type' => 'varchar',
                'php_type' => 'string',
                'size' => 255,
                'nullable' => false,
                'column_id' => 2,
                'primary' => false,
                'default_value' => '',
            ],
            [
                'name' => 'component_url',
                'native_type' => 'varchar',
                'php_type' => 'string',
                'size' => 255,
                'nullable' => false,
                'column_id' => 3,
                'primary' => false,
                'default_value' => '',
            ]
        ]
    ];


    protected const CONFIG_RUNTIME = [];


}