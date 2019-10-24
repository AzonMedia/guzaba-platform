<?php

namespace GuzabaPlatform\Components;


use Guzaba2\Orm\ActiveRecord;

class Component extends ActiveRecord
{

    protected const CONFIG_DEFAULTS = [
        'main_table'            => 'components',
    ];

    protected const CONFIG_RUNTIME = [];

    public function __construct()
    {
        parent::__construct();

    }
}