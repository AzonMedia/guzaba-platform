<?php

namespace GuzabaPlatform\Platform\Tests\Controllers;


use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Http\Method;
use Guzaba2\Mvc\Controller;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use GuzabaPlatform\Platform\Authentication\Models\User;

class Test extends Controller
{

    public const ROUTES = [
        GP::API_ROUTE_PREFIX.'/test2'      => [
            Method::HTTP_GET_HEAD_OPT               => [self::class, 'main'],
        ],
    ];

    protected const CONFIG_DEFAULTS = [
        'services'      => [
            'ConnectionFactory',
            'CurrentUser',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main()
    {
        //$struct = ['message' => 'ok'];
        //$Response = self::get_structured_ok_response($struct);
        $Connection = static::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR);
        //$Context = Coroutine::getContext();
        //print $Context->CurrentUser->get_id();
        //print self::get_service('CurrentUser')->get()->get_id();

        //$arr = \GuzabaPlatform\Platform\Tests\Models\Test::get_by( ['test_name' => 'some test value'] );
        $o1 = new \GuzabaPlatform\Platform\Tests\Models\Test(76);
        $o2 = new \GuzabaPlatform\Platform\Tests\Models\Test(78);
        //print_r(json_encode($o));

        //$str = 'test records: '.count($arr);
        //$str = 'all good';
        //$Response = self::get_string_ok_response($str);
        $Response = self::get_structured_ok_response([$o1, $o2]);
        return $Response;
    }
}