<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Tests\Controllers;


use Guzaba2\Database\Interfaces\ConnectionFactoryInterface;
use Guzaba2\Database\Interfaces\ConnectionInterface;
use Guzaba2\Database\Transaction;
use Guzaba2\Event\Event;
use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Application\BaseTestController;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TestTransactionDestruct
 * @package GuzabaPlatform\Platform\Tests\Controllers
 *
 * The tests are done on a database transaction
 */
class TestTransactionDestruct extends BaseTestController
{

    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/test/transaction/destruct'                        => [
                Method::HTTP_PUT                                           => [self::class, 'test_destruct'],
            ],
            '/test/transaction/nested/destruct'                 => [
                Method::HTTP_PUT                                           => [self::class, 'test_nested_destruct'],
            ],
            '/test/transaction/multiple/nested/destruct'        => [
                Method::HTTP_PUT                                           => [self::class, 'test_multiple_nested_destruct'],
            ],
        ],
        'services' => [
            'ConnectionFactory',
            'OrmStore',
        ]

    ];

    protected const CONFIG_RUNTIME = [];

    public function test_destruct(): ResponseInterface
    {

        $struct = ['total_events' => 3];

        /** @var ConnectionFactoryInterface $ConnectionFactory */
        $ConnectionFactory = self::get_service('ConnectionFactory');
        /** @var ConnectionInterface $Connection */
        $Connection = $ConnectionFactory->get_connection(MysqlConnectionCoroutine::class, $CR);
        /** @var Transaction $Transaction */
        $Transaction = $Connection->new_transaction($TR);
        $Transaction->add_callback('_before_destruct', function(Event $Event) use (&$struct) : void {
            //$struct['events'][] = '2: before transaction destruct';
            $struct['events'][2] = 'before transaction destruct';
        });
        $Transaction->begin();
        $Transaction->commit();

        //$struct['events'][] = '1: before unset scope reference and transaction vars';
        //always start from 1 not from 0 so that the resulting PHP array is not indexed and is encoded as JSON object
        $struct['events'][1] = 'before unset scope reference and transaction vars';
        unset($TR);
        unset($Transaction);
        //$struct['events'][] = '3: before sending response';
        $struct['events'][3] = 'before sending response';
        return self::get_test_response($struct);

    }

    public function test_nested_destruct(): ResponseInterface
    {
        $struct = ['total_events' => 4];

        /** @var ConnectionFactoryInterface $ConnectionFactory */
        $ConnectionFactory = self::get_service('ConnectionFactory');
        /** @var ConnectionInterface $Connection */
        $Connection = $ConnectionFactory->get_connection(MysqlConnectionCoroutine::class, $CR);
        /** @var Transaction $Transaction */
        $Transaction = $Connection->new_transaction($TR);
        $Transaction->add_callback('_before_destruct', function(Event $Event) use (&$struct) : void {
            $struct['events'][3] = 'before master transaction destruct';
        });
        $Transaction->begin();
        $this->nested_transaction($struct, 1);
        $Transaction->commit();

        $struct['events'][2] = 'before unset master scope reference and transaction vars';
        unset($TR);
        unset($Transaction);
        $struct['events'][4] = 'before sending response';
        return self::get_test_response($struct);
    }

    protected function nested_transaction(iterable &$struct, int $message_position): void
    {
        /** @var ConnectionFactoryInterface $ConnectionFactory */
        $ConnectionFactory = self::get_service('ConnectionFactory');
        /** @var ConnectionInterface $Connection */
        $Connection = $ConnectionFactory->get_connection(MysqlConnectionCoroutine::class, $CR);
        /** @var Transaction $Transaction */
        $Transaction = $Connection->new_transaction($TR);
        $Transaction->add_callback('_before_destruct', function(Event $Event) use (&$struct, $message_position) : void {
            $struct['events'][$message_position] = 'before child transaction destruct';
        });
        $Transaction->begin();
        $Transaction->commit();
    }

    public function test_multiple_nested_destruct(): ResponseInterface
    {
        $struct = ['total_events' => 9];

        /** @var ConnectionFactoryInterface $ConnectionFactory */
        $ConnectionFactory = self::get_service('ConnectionFactory');
        /** @var ConnectionInterface $Connection */
        $Connection = $ConnectionFactory->get_connection(MysqlConnectionCoroutine::class, $CR);
        /** @var Transaction $Transaction */
        $Transaction = $Connection->new_transaction($TR);
        $Transaction->add_callback('_before_destruct', function(Event $Event) use (&$struct) : void {
            $struct['events'][8] = 'before master transaction destruct';
        });
        $Transaction->begin();
        $this->multiple_nested_transaction($struct, 4, 1);
        $this->multiple_nested_transaction($struct, 5, 2);
        $this->multiple_nested_transaction($struct, 6, 3);
        //$this->multiple_nested_transaction($struct);
        //$this->multiple_nested_transaction($struct);
        $Transaction->commit();

        $struct['events'][7] = 'before unset master scope reference and transaction vars';
        unset($TR);
        unset($Transaction);
        $struct['events'][9] = 'before sending response';
        return self::get_test_response($struct);
    }

    protected function multiple_nested_transaction(iterable &$struct, int $message_position, int $nested_message_position): void
    {
        /** @var ConnectionFactoryInterface $ConnectionFactory */
        $ConnectionFactory = self::get_service('ConnectionFactory');
        /** @var ConnectionInterface $Connection */
        $Connection = $ConnectionFactory->get_connection(MysqlConnectionCoroutine::class, $CR);
        /** @var Transaction $Transaction */
        $Transaction = $Connection->new_transaction($TR);
        $Transaction->add_callback('_before_destruct', function(Event $Event) use (&$struct, $message_position) : void {
            $struct['events'][$message_position] = 'before first level child transaction destruct';
        });
        $Transaction->begin();
        $this->nested_transaction($struct, $nested_message_position);
        //$this->nested_transaction();
        $Transaction->commit();
    }
}