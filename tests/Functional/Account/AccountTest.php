<?php

namespace WalletAccountant\Tests\Functional\Account;

use Doctrine\DBAL\DBALException;
use function var_dump;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\Account;
use WalletAccountant\Domain\Account\Command\CreateAccount;
use WalletAccountant\Domain\Account\Command\UpdateAccountOwner;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPassword;
use WalletAccountant\Tests\Functional\FunctionalTestCase;
use WalletAccountant\Tests\Functional\Fixtures\Account\Account as AccountFixture;

/**
 * AccountTest
 */
class AccountTest extends FunctionalTestCase
{
    /**
     * @throws DBALException
     */
    public function testCreateAccount()
    {
        DateTime::setTestNow(DateTime::now());
        $bankId = 'f9145a03-69f7-4852-88d6-dedd330f839a';
        $ownerId = '6eeaf6b5-ce76-4d15-b370-5e148b93c8db';
        $iban = 'AD1200012030200359100100';

        $this->loadFixtures();
        $response = $this->login(UserWithPassword::EMAIL, UserWithPassword::PASSWORD);

        $client = self::createClient();
        $client->setAuthorizationTokenFromResponse($response);
        $client->post(
            '/account',
            [CreateAccount::BANK_ID => $bankId, CreateAccount::OWNER_ID => $ownerId, CreateAccount::IBAN => $iban]
        );
        $this->runProjection('account');

        $this->assertTrue($client->isCreatedAndJson());

        $accountId = AccountId::createFromString(json_decode($client->getContent(), true)['id']);

        $accountProjectionRepository = $this->container->get('test.account_projection_repository');
        $actualAccount = $accountProjectionRepository->getById($accountId);

        $expectedAccount = new Account(
            $accountId,
            BankId::createFromString($bankId),
            UserId::createFromString($ownerId),
            new Iban($iban)
        );

        $this->assertEquals($expectedAccount, $actualAccount);
    }

    /**
     * @throws DBALException
     */
    public function testUpdateAccountOwner(): void
    {
        DateTime::setTestNow(DateTime::now());
        $newOwnerId = '0960f730-824b-4253-ba32-43e508833733';

        $this->loadFixtures();
        $response = $this->login(UserWithPassword::EMAIL, UserWithPassword::PASSWORD);

        $accountProjectionRepository = $this->container->get('test.account_projection_repository');
        $previousAccount = $accountProjectionRepository->getById(
            AccountId::createFromString(AccountFixture::EVENT_AGGREGATE_ID)
        );
        $this->assertEquals(AccountFixture::OWNER_ID, $previousAccount->getOwnerId());

        $client = self::createClient();
        $client->setAuthorizationTokenFromResponse($response);
        $client->put(
            sprintf('/account/%s/owner', AccountFixture::EVENT_AGGREGATE_ID),
            [UpdateAccountOwner::OWNER_ID => $newOwnerId]
        );
        $this->runProjection('account');

        $this->assertTrue($client->isOkAndJson());

        $actualAccount = $accountProjectionRepository->getById(
            AccountId::createFromString(AccountFixture::EVENT_AGGREGATE_ID)
        );

        $expectedAccount = new Account(
            $previousAccount->getId(),
            $previousAccount->getBankId(),
            UserId::createFromString($newOwnerId),
            $previousAccount->getIban()
        );

        $this->assertEquals($expectedAccount, $actualAccount);
    }
}
