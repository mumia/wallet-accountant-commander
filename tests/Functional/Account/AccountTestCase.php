<?php

namespace WalletAccountant\Tests\Functional\Account;

use Doctrine\DBAL\DBALException;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\Account as AccountDocument;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPassword;
use WalletAccountant\Tests\Functional\FunctionalTestCase;
use WalletAccountant\Tests\Functional\TestClient;

/**
 * AccountTestCase
 */
class AccountTestCase extends FunctionalTestCase
{
    /**
     * @var TestClient
     */
    protected $client;

    /**
     * @throws DBALException
     */
    protected function setUp()
    {
        parent::setUp();

        DateTime::setTestNow(DateTime::now());
        $this->loadFixtures();
        $response = $this->login(UserWithPassword::EMAIL, UserWithPassword::PASSWORD);

        $this->client = self::createClient();
        $this->client->setAuthorizationTokenFromResponse($response);
    }

    /**
     * @param AccountDocument $expected
     * @param AccountDocument $actual
     */
    protected function assertEqualAccountProjections(AccountDocument $expected, AccountDocument $actual): void
    {
        $this->assertEquals($expected->getId(), $actual->getId());
        $this->assertEquals($expected->getBankId(), $actual->getBankId());
        $this->assertEquals($expected->getOwnerId(), $actual->getOwnerId());
        $this->assertEquals($expected->getIban(), $actual->getIban());
        $this->assertEquals($expected->getLedger()->movements(), $actual->getLedger()->movements());
    }
}
