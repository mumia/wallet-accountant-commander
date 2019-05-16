<?php

namespace WalletAccountant\Tests\Functional\User;

use Doctrine\DBAL\DBALException;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\User\Status;
use WalletAccountant\Tests\Functional\Fixtures\User\UserFixtures;
use WalletAccountant\Tests\Functional\FunctionalTestCase;

/**
 * UserInitiatePasswordRecoveryTest
 */
class UserInitiatePasswordRecoveryTest extends FunctionalTestCase
{
    /**
     * @throws DBALException
     */
    public function testUserInitiatePasswordRecovery()
    {
        DateTime::setTestNow(DateTime::now());

        $fixtures = static::$container->get('fixtures.loader.user');
        $fixtures->userWithPassword();

        $client = static::createClient();
        $client->enableProfiler();
        $client->request(Request::METHOD_POST, '/initiate-password-recovery', ['email' => UserFixtures::EMAIL]);

        // Make sure projection is updated with the new event
        $this->runProjection('user');

        $response = $client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('{"message":"user password recovery initiated"}', $response->getContent());

        $userProjectionRepository = $fixtures->getUserProjectionRepository();

        $actualUser = $userProjectionRepository->getByEmail(UserFixtures::EMAIL);

        $expectedStatus = new Status(false, false, true, true);
        $expectedExpiresOn = DateTime::now()->addHours(360);

        $this->assertEquals($expectedStatus, $actualUser->getStatus());
        $this->assertTrue($actualUser->hasRecovery());
        $this->assertTrue($expectedExpiresOn->sameValueAs($actualUser->getRecovery()->getExpiresOn()));

        $this->validateEmailWasSent($client);
    }

    /**
     * @param Client $client
     */
    private function validateEmailWasSent(Client $client)
    {
        /** @var MessageDataCollector $mailCollector */
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        /** @var Swift_Message $message */
        $message = $collectedMessages[0];

        $this->assertInstanceOf(Swift_Message::class, $message);
        $this->assertSame('Password recovery initiated', $message->getSubject());
        $this->assertSame('wlltccntnt@gmail.com', key($message->getFrom()));
        $this->assertSame(UserFixtures::EMAIL, key($message->getTo()));
    }
}
