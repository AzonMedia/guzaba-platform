<?php
declare(strict_types=1);

use Codeception\Util\HttpCode;

/**
 * Class TransactionDestructCest
 * Runs against @see \GuzabaPlatform\Tests\Controllers\TestCompositeTransactionDestruct
 *
 * Tests are the transactions destroyed when the master transaction is committed or rolled back and the reference to it ceases to exist.
 *
 * The tests are done on an ORM transaction (composite transaction)
 */
class CompositeTransactionDestructCest
{
    public function _before(ApiTester $I): void
    {
    }

    // tests
    public function test_destruct(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/composite/destruct');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function test_nested_destruct(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/composite/nested/destruct');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

    public function test_multiple_nested_destruct(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/composite/multiple/nested/destruct');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }
}
