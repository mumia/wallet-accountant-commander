<?php

namespace WalletAccountant\Tests\Functional\Bank;

use Doctrine\DBAL\DBALException;
use function json_decode;
use function sprintf;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\Bank;
use WalletAccountant\Document\Common\Authored;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Tests\Functional\Fixtures\Bank\Bank as BankFixture;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPassword;
use WalletAccountant\Tests\Functional\FunctionalTestCase;

/**
 * BankTest
 */
class BankTest extends FunctionalTestCase
{
    /**
     * @throws DBALException
     */
    public function testCreateBank(): void
    {
        DateTime::setTestNow(DateTime::now());
        $bankName = 'bank_name';

        $this->loadFixtures();
        $response = $this->login(UserWithPassword::EMAIL, UserWithPassword::PASSWORD);

        $client = self::createClient();
        $client->setAuthorizationTokenFromResponse($response);
        $client->post('/bank', ['name' => $bankName]);
        $this->runProjection('bank');

        $this->assertTrue($client->isCreatedAndJson());

        $bankId = BankId::createFromString(json_decode($client->getContent(), true)['id']);

        $bankProjectionRepository = $this->container->get('test.bank_projection_repository');
        $actualBank = $bankProjectionRepository->getById($bankId);

        $authoredBy = UserId::createFromString('6eeaf6b5-ce76-4d15-b370-5e148b93c8db');
        $expectedAuthored = new Authored($authoredBy, DateTime::now());
        $expectedBank = new Bank($bankId, $bankName, $expectedAuthored, $expectedAuthored);

        $this->assertEquals($expectedBank, $actualBank);
    }

    /**
     * @throws DBALException
     */
    public function testUpdateBank(): void
    {
        DateTime::setTestNow(DateTime::now());
        $bankNameUpdate = 'bank_name_update';

        $this->loadFixtures();
        $response = $this->login(UserWithPassword::EMAIL, UserWithPassword::PASSWORD);

        $bankProjectionRepository = $this->container->get('test.bank_projection_repository');
        $previousBank = $bankProjectionRepository->getById(
            BankId::createFromString(BankFixture::EVENT_AGGREGATE_ID)
        );
        $this->assertEquals(BankFixture::NAME, $previousBank->getName());

        $client = self::createClient();
        $client->setAuthorizationTokenFromResponse($response);
        $client->put(sprintf('/bank/%s', BankFixture::EVENT_AGGREGATE_ID), ['name' => $bankNameUpdate]);
        $this->runProjection('bank');

        $this->assertTrue($client->isOkAndJson());

        $actualBank = $bankProjectionRepository->getById(
            BankId::createFromString(BankFixture::EVENT_AGGREGATE_ID)
        );

        $authoredBy = UserId::createFromString(UserWithPassword::EVENT_AGGREGATE_ID);
        $expectedAuthoredCreated = new Authored($authoredBy, DateTime::now()->subHours(10));
        $expectedAuthoredUpdated = new Authored($authoredBy, DateTime::now());
        $expectedBank = new Bank(
            BankId::createFromString(BankFixture::EVENT_AGGREGATE_ID),
            $bankNameUpdate,
            $expectedAuthoredCreated,
            $expectedAuthoredUpdated
        );


        $this->assertEquals($expectedBank, $actualBank);
    }
}
