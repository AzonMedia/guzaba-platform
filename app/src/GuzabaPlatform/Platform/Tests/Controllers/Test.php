<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Tests\Controllers;


use Guzaba2\Authorization\Acl\Permission;
use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Http\Method;
use Guzaba2\Mvc\Controller;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use GuzabaPlatform\Platform\Authentication\Models\User;

class Test extends Controller
{

//    public const ROUTES = [
//        GP::API_ROUTE_PREFIX.'/test2'      => [
//            Method::HTTP_GET_HEAD_OPT               => [self::class, 'main'],
//            Method::HTTP_POST                       => [self::class, 'post'],
//        ],
//    ];

    protected const CONFIG_DEFAULTS = [
        'services'      => [
//            'ConnectionFactory',
//            'CurrentUser',
        ],
        'routes'        => [
            '/test2'      => [
                Method::HTTP_GET_HEAD_OPT               => [self::class, 'main'],
                Method::HTTP_POST                       => [self::class, 'post'],
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main()
    {
        //$struct = ['message' => 'ok'];
        //$Response = self::get_structured_ok_response($struct);
        //$Connection = static::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR);
        //$Context = Coroutine::getContext();
        //print $Context->CurrentUser->get_id();
        //print self::get_service('CurrentUser')->get()->get_id();
        //self::get_service('CurrentUser');

//        $f1 = function() {
//            return 'f1';
//        };
//        $f2 = function() {
//            return 'f2';
//        };
//        $ret = Coroutine::executeMulti($f1, $f2);
//        print_r($ret);
//        $ret = Coroutine::executeMulti($f1, $f2);
//        print_r($ret);

        //$arr = \GuzabaPlatform\Platform\Tests\Models\Test::get_by( ['test_name' => 'some test value'] );
        //$o1 = new \GuzabaPlatform\Platform\Tests\Models\Test(76);
        //$o2 = new \GuzabaPlatform\Platform\Tests\Models\Test(78);
        $o2 = new \GuzabaPlatform\Platform\Tests\Models\Test(88);
        //print_r(json_encode($o));

        //$str = 'test records: '.count($arr);
        //$str = 'all good';
        //$Response = self::get_string_ok_response($str);
        //$Response = self::get_structured_ok_response([$o1, $o2]);
        //$Response = self::get_structured_ok_response([$o2]);

//        $data = Permission::get_data_by([
//            'class_name' => 'GuzabaPlatform\\Platform\\Tests\\Models\\Test',
//            'object_id' => 78,
//            'action_name' => 'read',
//        ]);
//        print_r($data);

        //$Response = self::get_structured_ok_response(['message' => 'ok']);
        $Response = self::get_structured_ok_response([$o2]);
        $Response = $Response->withHeader('data-origin','orm-specific');
        return $Response;
    }

    public function post()
    {
//        $o1 = new \GuzabaPlatform\Platform\Tests\Models\Test(76);
//        $o2 = new \GuzabaPlatform\Platform\Tests\Models\Test(76);
//        $o1->test_name ='asdasdasd';
//        $o1->test_name ='asdasdasd22222';
//        print $o2->test_name;

        $tests_data = \GuzabaPlatform\Platform\Tests\Models\Test::get_data_by([]);
        print_r($tests_data);
//        foreach ($tests_data as $record) {
//            $Test = new \GuzabaPlatform\Platform\Tests\Models\Test($record['test_id']);
//            $Test->delete();
//        }

        $Response = self::get_structured_ok_response(['message' => 'okkk']);
        return $Response;
    }
}