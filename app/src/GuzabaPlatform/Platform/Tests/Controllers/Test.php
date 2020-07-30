<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Tests\Controllers;

use Azonmedia\Exceptions\InvalidArgumentException;
use Guzaba2\Authorization\Acl\Permission;
use Guzaba2\Authorization\Role;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Coroutine\Resources;
use Guzaba2\Database\Interfaces\ConnectionFactoryInterface;
use Guzaba2\Database\Interfaces\ConnectionInterface;
use Guzaba2\Event\Event;
use Guzaba2\Http\Method;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Kernel\Runtime;
use Guzaba2\Mvc\ActiveRecordController;
use Guzaba2\Mvc\Controller;
use Guzaba2\Mvc\ExecutorMiddleware;
use Guzaba2\Orm\ActiveRecord;
use Guzaba2\Orm\Interfaces\ActiveRecordInterface;
use Guzaba2\Orm\OrmTransactionalResource;
use Guzaba2\Orm\ScopeManager;
use Guzaba2\Orm\Store\Memory;
use Guzaba2\Orm\Transaction;
use Guzaba2\Swoole\IpcRequest;
use Guzaba2\Swoole\Server;
use Guzaba2\Transaction\TransactionManager;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use GuzabaPlatform\Platform\Authentication\Models\User;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Components\Models\Component;
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
            '/test-object-transactions'    => [
                Method::HTTP_PUT                        => [self::class, 'test_object_transactions'],
            ],
            '/test-orm-transactions'    => [
                Method::HTTP_PUT                        => [self::class, 'test_orm_transactions'],
            ],
            '/request-test'     => [
                Method::HTTP_GET                        => [self::class, 'request_test'],
            ],
            '/ipc-test-init'    => [
                Method::HTTP_GET                        => [self::class, 'ipc_test_init'],
            ],
            '/ipc-test-responder'    => [
                Method::HTTP_GET                        => [self::class, 'ipc_test_responder'],
            ],
            '/test-sub-co'          => [
                Method::HTTP_GET                        => [self::class, 'sub_co'],
            ],
            '/test-with-arg/{arg}'  => [
                Method::HTTP_GET                        => [self::class, 'test_with_arg'],
            ],
            '/post-test-with-arg'  => [
                Method::HTTP_POST                       => [self::class, 'post_test_with_arg'],
            ],
            '/trigger-exception'    => [
                Method::HTTP_GET                        => [self::class, 'trigger_exception'],
            ],
            '/fix-permissions'      => [
                Method::HTTP_POST                       => [self::class, 'fix_permissions'],
            ],
            '/fix-controllers-permissions'      => [
                Method::HTTP_POST                       => [self::class, 'fix_controllers_permissions'],
            ],
        ],
        'services' => [
            'ConnectionFactory',
            'OrmStore',
            'Server',
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
     * @throws InvalidArgumentException
     */
    public function _init(?string $language = NULL)
    {
        if ($language) {
            t::set_target_language($language, $this->get_request());
        }

    }

    public function fix_permissions(): ResponseInterface
    {
//        //for each record in object-meta there must be the appropriate permission records for the owner
//        $Connection = static::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $SR);
//        $q = "
//SELECT meta_object_id, meta_class_id FROM guzaba_object_meta
//        ";
//        $data = $Connection->prepare($q)->execute()->fetchAll();
//        //print_r($data);
//        $AdminUser = new User(32);
//        $AdminRole = $AdminUser->get_role();
//        foreach ($data as $record) {
//            try {
//                $class_name = ActiveRecord::get_class_name($record['meta_class_id']);
//                if ($class_name === Component::class) {
//                    continue;
//                }
//                $Object = new $class_name($record['meta_object_id']);
//                $object_actions = $Object::get_object_actions();
//                foreach ($object_actions as $object_action) {
//                    $Object->grant_permission($AdminRole, $object_action);
//                    print 'granted '.$object_action.' on '.$class_name.PHP_EOL;
//                }
//
//            } catch (\Exception $Exception) {
//                print $Exception->getMessage().PHP_EOL;
//            }
//        }

        return self::get_structured_ok_response( ['message' => 'ok'] );
    }

    public function fix_controllers_permissions(): ResponseInterface
    {

//        $AdminRole = new Role(\GuzabaPlatform\Platform\Authentication\Roles\ADMINISTRATOR);
//        $controller_classes = Controller::get_controller_classes();
//        foreach ($controller_classes as $class) {
//            foreach ($class::get_actions() as $action) {
//                try {
//                    /** @var ActiveRecordInterface */
//                    $class::grant_class_permission($AdminRole, $action);
//                    print 'granted '.$action.' on '.$class.PHP_EOL;
//                } catch (\Exception $Exception) {
//                    print $Exception->getMessage().PHP_EOL;
//                }
//            }
//        }

        return self::get_structured_ok_response( ['message' => 'ok'] );
    }

    public function trigger_exception(): ResponseInterface
    {
        print $aaa;
    }

    public function test_with_arg(string $arg): ResponseInterface
    {
        $struct = ['mesage' => 'provided argument is '.$arg];
        return self::get_structured_ok_response($struct);
    }

    public function post_test_with_arg(string $arg): ResponseInterface
    {
        $struct = ['mesage' => 'posted argument is '.$arg];
        return self::get_structured_ok_response($struct);
    }

    public function sub_co(): ResponseInterface
    {
        $f1 = function() {
            Coroutine::sleep(1);
            return 'response from f1';
        };
        $f2 = function() {
            Coroutine::sleep(12);
            return 'response from f2';
        };
        $responses = Coroutine::executeMulti($f1, $f2);
        print_r($responses);
        return self::get_structured_ok_response();
    }

    /**
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws RunTimeException
     * @throws \Guzaba2\Base\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\LogicException
     * @throws \Guzaba2\Coroutine\Exceptions\ContextDestroyedException
     * @throws \Guzaba2\Kernel\Exceptions\ConfigurationException
     * @throws \ReflectionException
     */
    public function ipc_test_init() : ResponseInterface
    {

        /** @var Server $Server */
        $Server = self::get_service('Server');

        $IpcRequest = new IpcRequest(Method::HTTP_GET, '/api/ipc-test-responder');
        $start = microtime(TRUE);
        $IpcResponse = $Server->send_ipc_request($IpcRequest, 4);//4 is the worker ID
        $end = microtime(TRUE);
        $Body = $IpcResponse->getBody();
        $ipc_response_struct = $Body->getStructure();
        return self::get_structured_ok_response(['message' => 'ipc init', 'ipc_response' => $ipc_response_struct['ipc_message'], 'time' => $end - $start ]);
    }

    public function ipc_test_responder(): ResponseInterface
    {
        /** @var Server $Server */
        $Server = self::get_service('Server');
        $worker_id = $Server->get_worker_id();
        $Response = self::get_structured_ok_response(['message' => 'ok', 'ipc_message' => rand(1,1000).' some message here from worker '.$worker_id]);
        return $Response;
    }

    public function request_test(): ResponseInterface
    {
        $Request = $this->get_request();
        print_r($Request->getServerParams());
        return self::get_structured_ok_response(['message' => 'ok']);
    }

    public function test_orm_transactions() : ResponseInterface
    {
        $Transaction = ActiveRecord::new_transaction($TR);
        $Transaction->begin();
        $Test = new \GuzabaPlatform\Platform\Tests\Models\Test(121);
        $Test->test_name = 'asdasd 4445 66';
        //$Transaction->rollback();

        $this->test_orm_nested_transaction();


        $Test->write();
        $Transaction->commit();
        print $Test->test_name.PHP_EOL;//some test value 333 expected
//unset($TR);
        $Context = Coroutine::getContext();
        $Resources = $Context->{Resources::class};
        print 'Resources: '.count($Resources->get_resources());

        return self::get_structured_ok_response(['message' => 'ok']);
    }

    protected function test_orm_nested_transaction() : void
    {
        $Transaction = ActiveRecord::new_transaction($TR);
        $Transaction->add_callback('_before_rollback', function(Event $Event) : void {
            /** @var Transaction $Transaction */
            $Transaction = $Event->get_subject();
            print $Transaction->get_rollback_reason();
        });
        $Transaction->begin();
        $Test = new \GuzabaPlatform\Platform\Tests\Models\Test(122);
        $Test->test_name = 'ffggg';
    }

    public function test_object_transactions() : ResponseInterface
    {
        /** @var Memory $Memory */
        $Memory = self::get_service('OrmStore');
        /** @var Transaction $Transaction */
        $Transaction = $Memory->new_transaction($TR);
        $Transaction->begin();
        $Test = new \GuzabaPlatform\Platform\Tests\Models\Test(121);
        $Test->test_name = 'asdasd';
        $this->test_nested_object_transactions();
        //$Transaction->rollback();
        $Transaction->commit();
        print $Test->test_name.PHP_EOL;//some test value 333 expected



        return self::get_structured_ok_response();
    }

    protected function test_nested_object_transactions() : void
    {
        /** @var Memory $Memory */
        $Memory = self::get_service('OrmStore');
        /** @var Transaction $Transaction */
        $Transaction = $Memory->new_transaction($TR);
        $Transaction->begin();
        $Test = new \GuzabaPlatform\Platform\Tests\Models\Test(122);
        $Test->test_name = 'asdasd fff';
    }

    /**
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws RunTimeException
     * @throws \Guzaba2\Coroutine\Exceptions\ContextDestroyedException
     */
    public function test_transactions() : ResponseInterface
    {
        /** @var TransactionManager $TXM */
        //$TXM = self::get_service('TransactionManager');//not needed

        /** @var ConnectionFactoryInterface $ConnectionFactory */
        //$ConnectionFactory = self::get_service('ConnectionFactory');
        /** @var ConnectionInterface $Connection */
        //$Connection = $ConnectionFactory->get_connection(MysqlConnectionCoroutine::class, $CR);
        //print $Connection->get_object_internal_id().' '.$Connection->get_resource_id().PHP_EOL;
        //$TXM->begin_transaction($Connection, $TR);//no need of scope reference here as the resource (connection) already has one
        //if the resource scope reference is free

        /** @var ConnectionFactoryInterface $ConnectionFactory */
        $ConnectionFactory = self::get_service('ConnectionFactory');
        /** @var ConnectionInterface $Connection */
        $Connection = $ConnectionFactory->get_connection(MysqlConnectionCoroutine::class, $CR);
        //print $Connection->get_object_internal_id().' '.$Connection->get_resource_id().PHP_EOL;
        //$TXM->begin_transaction($Connection, $TR);//no need of scope reference here as the resource (connection) already has one
        //if the resource scope reference is freed (ref count --) this should also invoke a transaction rollback on the current transaction
        //there is no need to have an explicit reference to the transaction by the scope reference
        //$TXM->begin_transaction($Connection);d (ref count --) this should also invoke a transaction rollback on the current transaction
        //there is no need to have an explicit reference to the transaction by the scope reference
        //$TXM->begin_transaction($Connection);
        //$Connection->begin_transaction();
        //it will be needed to have ScopeReference in the new_transaction() as in the case of nested calls & connections & transactions
        //one scope may have connection + transaction and the next nested scope to have only a connection
        //and if a rollback is done on the transaction on the free_connection this will rollback the parent transaction
        $Transaction = $Connection->new_transaction($TR);

        //alternative
        //$MemoryTransaction = self::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR)->new_transaction($TR);

        $Transaction->begin();


        $this->test_nested_transaction();

        $Transaction->commit();

//        unset($TR);
//        unset($CR);
//        $Context = Coroutine::getContext();
//        $Resources = $Context->{Resources::class};
//        print 'Resources: '.count($Resources->get_resources()).PHP_EOL;

        return self::get_structured_ok_response(['message' => 'ok']);
    }

    protected function test_nested_transaction() : void
    {
        //$MemoryTransaction = self::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR)->new_transaction($TR);
        /** @var ConnectionFactoryInterface $ConnectionFactory */
        $ConnectionFactory = self::get_service('ConnectionFactory');
        /** @var ConnectionInterface $Connection */
        $Connection = $ConnectionFactory->get_connection(MysqlConnectionCoroutine::class, $CR);
        //print $Connection->get_object_internal_id().' '.$Connection->get_resource_id().PHP_EOL;
        $Transaction = $Connection->new_transaction($TR);
        $Transaction->add_callback('_before_rollback', function(Event $Event) : void
        {
            $Transaction = $Event->get_subject();
            print $Transaction->get_rollback_reason().PHP_EOL;
        });
        $Transaction->begin();
//        $MemoryTransaction->add_callback('_before_rollback', function(){
//            print 'AAAAAAAAAAAA';
//        });
//        $MemoryTransaction->add_callback('_before_commit', function(){
//            print 'BBBBBBBBBBBB';
//        });

        $this->test_nested_transaction_2();

        //$MemoryTransaction->commit();
    }

    protected function test_nested_transaction_2() : void
    {
        $Transaction = self::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR)->new_transaction($TR);
        $Transaction->add_callback('_before_rollback', function(Event $Event) : void
        {
            $Transaction = $Event->get_subject();
            print $Transaction->get_rollback_reason().PHP_EOL;
        });
        $Transaction->begin();
        $Transaction->commit();
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