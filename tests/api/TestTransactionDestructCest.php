<?php

use Codeception\Util\HttpCode;

class TestTransactionDestructCest
{
    public function _before(ApiTester $I): void
    {
    }

    // tests
    public function test_destruct(ApiTester $I): void
    {
        $I->sendPUT('/test/transaction/destruct');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function test_nested_destruct(ApiTester $I): void
    {
        $I->sendPUT('/test/transaction/nested/destruct');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

    public function test_multiple_nested_destruct(ApiTester $I): void
    {
        $I->sendPUT('/test/transaction/multiple/nested/destruct');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }
}
