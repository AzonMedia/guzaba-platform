<?php

namespace GuzabaPlatform\Platform\Tests\Models;

use GuzabaPlatform\Platform\Application\BaseActiveRecord;

class Test extends BaseActiveRecord
{
    protected const CONFIG_DEFAULTS = [
        'main_table'            => 'tests',
        'route'                 => '/test',

        'load_in_memory'        => TRUE,
    ];

    protected const CONFIG_RUNTIME = [];
}