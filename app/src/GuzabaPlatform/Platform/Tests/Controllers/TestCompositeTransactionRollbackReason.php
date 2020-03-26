<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Tests\Controllers;

use Guzaba2\Http\Method;
use Psr\Http\Message\ResponseInterface;

class TestCompositeTransactionRollbackReason
{

    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/test/transaction/composite/rollback/reason/implicit'      => [
                Method::HTTP_POST                                           => [self::class, 'test_implicit_rollback'],
            ],
            '/test/transaction/composite/rollback/reason/explicit'      => [
                Method::HTTP_POST                                           => [self::class, 'test_explicit_rollback'],
            ],
        ],
        'services' => [
            'ConnectionFactory',
            'OrmStore',
        ]

    ];

    protected const CONFIG_RUNTIME = [];

    public function test_implicit_rollback(): ResponseInterface
    {

    }

    public function test_explicit_rollback(): ResponseInterface
    {

    }


    public function test_exception_rollback(): ResponseInterface
    {

    }




    public function test_nested_implicit_rollback(): ResponseInterface
    {

    }

    public function test_nested_parent_rollback(): ResponseInterface
    {

    }

    public function test_nested_explicit_rollback(): ResponseInterface
    {

    }

    public function test_nested_exception_rollback(): ResponseInterface
    {

    }




    public function test_multiple_nested_composite_transaction_exception_rollback(): ResponseInterface
    {

    }
}