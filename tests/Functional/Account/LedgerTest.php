<?php

namespace WalletAccountant\Tests\Functional\Account;

use function sprintf;
use function var_dump;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\Account;
use WalletAccountant\Document\Account\Ledger;
use WalletAccountant\Document\Account\Movement;
use WalletAccountant\Document\Common\Money;
use WalletAccountant\Domain\Account\Command\AddMovementToLedger;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Account\Ledger\Id\MovementId;
use WalletAccountant\Domain\Account\Ledger\Type\Type;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\CurrencyCode;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Tests\Functional\Fixtures\Account\Account as AccountFixture;

/**
 * LedgerTest
 */
class LedgerTest extends AccountTestCase
{
    public function testAddMovement()
    {
        $accountId = AccountId::createFromString(AccountFixture::EVENT_AGGREGATE_ID);
        $type = 'debit';
        $amount = 100000;
        $description = 'test description';
        $processedOn = DateTime::now()->subDay(1);

        $this->client->put(
            sprintf('/account/%s/movement', $accountId),
            [
                AddMovementToLedger::TYPE => $type,
                AddMovementToLedger::AMOUNT => $amount,
                AddMovementToLedger::DESCRIPTION => $description,
                AddMovementToLedger::PROCESSED_ON => $processedOn->toDateTime()
            ]
        );
        $this->runProjection('account');

        $this->assertTrue($this->client->isOkAndJson());

        $accountProjectionRepository = $this->container->get('test.account_projection_repository');
        $actualAccount = $accountProjectionRepository->getById($accountId);

        /** @var Movement $movement */
        $movement = $actualAccount->getLedger()->movements()[0];
        $expectedMovement = new Movement(
            MovementId::createFromString($movement->getId()->toString()), // Auto generated, so it will be ignored
            Type::createFromString($type),
            new Money($amount, CurrencyCode::createEuro()),
            $description,
            DateTime::createFromDateTimeFormat($processedOn->toDateTime())
        );
        $expectedAccount = new Account(
            $accountId,
            BankId::createFromString(AccountFixture::BANK_ID),
            UserId::createFromString(AccountFixture::OWNER_ID),
            new Iban(AccountFixture::IBAN),
            new Ledger([$expectedMovement])
        );

        $this->assertEqualAccountProjections($expectedAccount, $actualAccount);
    }
}
