<?php

namespace WalletAccountant\Tests\Functional\User;

use Doctrine\DBAL\DBALException;
use function sprintf;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\User\Recovery;
use WalletAccountant\Document\User\Status;
use WalletAccountant\Infrastructure\MongoDB\UserProjectionRepository;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPassword;
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

        $this->loadFixtures();

        $client = static::createClient();
        $client->enableProfiler();
        $client->request(Request::METHOD_POST, '/initiate-password-recovery', ['email' => UserWithPassword::EMAIL]);

        // Make sure projection is updated with the new event
        $this->runProjection('user');

        $response = $client->getResponse();

        if (!$response instanceof Response) {
            $this->fail(sprintf('response in not an %s object', Response::class));
        }

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('{"message":"user password recovery initiated"}', $response->getContent());

        $userProjectionRepository = $this->container->get('test.user_projection_repository');

        $actualUser = $userProjectionRepository->getByEmail(UserWithPassword::EMAIL);

        $expectedStatus = new Status(false, false, true, true);
        $expectedExpiresOn = DateTime::now()->addHours(360);

        $this->assertEquals($expectedStatus, $actualUser->getStatus());
        $this->assertTrue($actualUser->hasRecovery());

        $recovery = $actualUser->getRecovery();
        if (!$recovery instanceof Recovery) {
            $this->fail(sprintf('recovery in not an %s object', Recovery::class));
        }

        $this->assertTrue($expectedExpiresOn->sameValueAs($recovery->getExpiresOn()));

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
        $this->assertSame(UserWithPassword::EMAIL, key($message->getTo()));
    }
}
