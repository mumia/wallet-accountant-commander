<?php

namespace WalletAccountant\Tests\Functional\Account;

use WalletAccountant\Document\Account;
use WalletAccountant\Document\Account\Ledger;
use WalletAccountant\Domain\Account\Command\CreateAccount;
use WalletAccountant\Domain\Account\Command\UpdateAccountOwner;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Tests\Functional\Fixtures\Account\Account as AccountFixture;

/**
 * AccountTest
 */
class AccountTest extends AccountTestCase
{
    public function testCreateAccount()
    {
        $bankId = 'f9145a03-69f7-4852-88d6-dedd330f839a';
        $ownerId = '6eeaf6b5-ce76-4d15-b370-5e148b93c8db';
        $iban = 'AD1200012030200359100100';

        $this->client->post(
            '/account',
            [CreateAccount::BANK_ID => $bankId, CreateAccount::OWNER_ID => $ownerId, CreateAccount::IBAN => $iban]
        );
        $this->runProjection('account');

        $this->assertTrue($this->client->isCreatedAndJson());

        $accountId = AccountId::createFromString(json_decode($this->client->getContent(), true)['id']);

        $accountProjectionRepository = $this->container->get('test.account_projection_repository');
        $actualAccount = $accountProjectionRepository->getById($accountId);

        $expectedAccount = new Account(
            $accountId,
            BankId::createFromString($bankId),
            UserId::createFromString($ownerId),
            new Iban($iban),
            new Ledger()
        );

        $this->assertEqualAccountProjections($expectedAccount, $actualAccount);
    }

    public function testUpdateAccountOwner(): void
    {
        $newOwnerId = '0960f730-824b-4253-ba32-43e508833733';

        $accountProjectionRepository = $this->container->get('test.account_projection_repository');
        $previousAccount = $accountProjectionRepository->getById(
            AccountId::createFromString(AccountFixture::EVENT_AGGREGATE_ID)
        );
        $this->assertEquals(AccountFixture::OWNER_ID, $previousAccount->getOwnerId());

        $this->client->put(
            sprintf('/account/%s/owner', AccountFixture::EVENT_AGGREGATE_ID),
            [UpdateAccountOwner::OWNER_ID => $newOwnerId]
        );
        $this->runProjection('account');

        $this->assertTrue($this->client->isOkAndJson());

        $actualAccount = $accountProjectionRepository->getById(
            AccountId::createFromString(AccountFixture::EVENT_AGGREGATE_ID)
        );

        $expectedAccount = new Account(
            $previousAccount->getId(),
            $previousAccount->getBankId(),
            UserId::createFromString($newOwnerId),
            $previousAccount->getIban(),
            $previousAccount->getLedger()
        );

        $this->assertEqualAccountProjections($expectedAccount, $actualAccount);
    }
}
