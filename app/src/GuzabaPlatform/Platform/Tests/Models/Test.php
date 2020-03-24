<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Tests\Models;

use GuzabaPlatform\Platform\Application\BaseActiveRecord;

/**
 * Class Test
 * @package GuzabaPlatform\Platform\Tests\Models
 *
 * @property int test_id
 * @property string test_name
 */
class Test extends BaseActiveRecord
{
    protected const CONFIG_DEFAULTS = [
        'main_table'            => 'tests',
        'route'                 => '/test',

        'object_name_column'    => 'test_name',

        'load_in_memory'        => TRUE,
    ];

    protected const CONFIG_RUNTIME = [];

    public function f1()
    {

    }

    /**
     * @check_permissions
     */
    public function f2()
    {

    }

    public static function f3()
    {

    }

    /**
     * @check_permissions
     */
    public static function f4()
    {

    }
}