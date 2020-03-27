<?php
declare(strict_types=1);

use Codeception\Util\HttpCode;

class TransactionRollbackReasonCest
{
    public function test_implicit_rollback_by_conn_ref(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/rollback/reason/implicit/conn-ref');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function test_implicit_rollback_by_trans_ref(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/rollback/reason/implicit/trans-ref');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}