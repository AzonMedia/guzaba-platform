<?php 

class TestTransactionDestructCest
{
    public function _before(ApiTester $I): void
    {
    }

    // tests
    public function test_destruct(ApiTester $I): void
    {
        $I->sendPUT('/test/transaction/destruct');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

//    public function test_nested_destruct(): void
//    {
//
//    }
//
//    public function test_multiple_nested_destruct(): void
//    {
//
//    }
}
