<?php
declare(strict_types=1);

use Codeception\Util\HttpCode;

/**
 * Class TransactionRollbackReasonCest
 *
 * Tests @see \GuzabaPlatform\Tests\Controllers\TestTransactionRollbackReason
 */
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

    public function test_explicit_rollback(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/rollback/reason/explicit');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function test_base_exception_rollback(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/rollback/reason/exception/base');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function test_exception_rollback(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/rollback/reason/exception');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function test_parent_rollback(ApiTester $I): void
    {
        $I->sendPUT('/tests/transaction/rollback/reason/parent');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}