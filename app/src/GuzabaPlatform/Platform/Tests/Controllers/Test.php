<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Tests\Controllers;

use Guzaba2\Authorization\Acl\Permission;
use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Database\Interfaces\ConnectionInterface;
use Guzaba2\Http\Method;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Mvc\ActiveRecordController;
use Guzaba2\Mvc\ExecutorMiddleware;
use Guzaba2\Transaction\TransactionManager;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use GuzabaPlatform\Platform\Authentication\Models\User;
use Guzaba2\Translator\Translator as t;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;

class Test extends BaseController
{

//    public const ROUTES = [
//        GP::API_ROUTE_PREFIX.'/test2'      => [
//            Method::HTTP_GET_HEAD_OPT               => [self::class, 'main'],
//            Method::HTTP_POST                       => [self::class, 'post'],
//        ],
//    ];

    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/test2'      => [
                Method::HTTP_GET                        => [self::class, 'main'],
                Method::HTTP_POST                       => [self::class, 'post'],
            ],
            '/test3'      => [
                Method::HTTP_GET                        => [self::class, 'test3'],
            ],
            '/test4'      => [
                Method::HTTP_GET                        => [self::class, 'test4'],
            ],
            '/test5'      => [
                Method::HTTP_GET                        => [self::class, 'test5'],
            ],
            '/test-transactions'    => [
                Method::HTTP_GET                        => [self::class, 'test_transactions'],
            ],
        ],
        'services' => [
            'ConnectionFactory',
            //'TransactionManager',
        ]

    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * Example setting the language
     * The preferred way is to leave the value blank and if there is a value to set the target language
     * instead of having the default target language provided as default value to the parameter as this creates dependency on an external class (GuzabaPlatform)
     * and also will require accessing a public constant (as the RUNTIME_CONFIG is protected)
     * If this needs to be added on all controllers then the _init() method can se set on the BaseController
     * If the provided language is not supported this will trigger a notice and the target language will not be changed
     * @param string $language
     * @throws \Azonmedia\Exceptions\RunTimeException
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     */
    public function _init(?string $language = NULL)
    {
        t::set_target_language($language, $this->get_request());
    }

    public function test_transactions() : ResponseInterface
    {
        /** @var TransactionManager $TXM */
        //$TXM = self::get_service('TransactionManager');
        /** @var ConnectionInterface $Connection */
        $Connection = self::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR);
        //$TXM->begin_transaction($Connection, $TR);//no need of scope reference here as the resource (connection) already has one
        //if the resource scope reference is freed (ref count --) this should also invoke a transaction rollback on the current transaction
        //there is no need to have an explicit reference to the transaction by the scope reference
        //$TXM->begin_transaction($Connection);
        //$Connection->begin_transaction();
        $Transaction = $Connection->new_transaction();
        $Transaction->begin();

        return self::get_structured_ok_response(['message' => 'ok']);
    }

    public function test5() : ResponseInterface
    {
        //print t::_('test message');
        $struct = ['text4' => t::_('test message')];
        return self::get_structured_ok_response($struct);
    }

    public function test3(int $arg1 = 0) : ResponseInterface
    {
        //$actions = \GuzabaPlatform\Platform\Tests\Models\Test::get_object_actions();
        //print_r($actions);
        $struct = ['message' => 'this is a test method'];
        //examples how to invoke a nested controller
        //$struct['test4'] = $this->execute_structured_method('test4');
        $struct += $this->execute_structured_action('test4', ['a' => 'gggg']);
        //$struct += $this->test4('asdasd');//will not fire the events...

        //maybe in future
        //$struct += $this('test4');
        //$struct += $this('test4', $arg1, $arg2);//__invoke() using execute_structured_action
        //$this->{'+test4'}();
        $Response = self::get_structured_ok_response($struct);

        return $Response;
    }

    public function test4(string $a) : ResponseInterface
    {
        $struct = ['text4' => 'text from test4 '.$a];
        return self::get_structured_ok_response($struct);
    }

    public function main() : ResponseInterface
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

    public function post() : ResponseInterface
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