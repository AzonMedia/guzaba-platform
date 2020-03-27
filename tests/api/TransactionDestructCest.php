<?php
declare(strict_types=1);

use Codeception\Util\HttpCode;

/**
 * Class TransactionDestructCest
 * Runs against @see \GuzabaPlatform\Tests\Controllers\TestTransactionDestruct
 */
class TransactionDestructCest
{
    public function _before(ApiTester $I): void
    {
    }

    // tests
    public function test_destruct(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/destruct');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function test_nested_destruct(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/nested/destruct');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

    public function test_multiple_nested_destruct(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/multiple/nested/destruct');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }
}
