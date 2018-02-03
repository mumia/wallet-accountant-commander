<?php

namespace WalletAccountant\Tests\Functional\User;

use Doctrine\DBAL\DBALException;
use function sprintf;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function var_dump;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\User\Recovery;
use WalletAccountant\Document\User\Status;
use WalletAccountant\Domain\User\Command\RecoverUserPassword;
use WalletAccountant\Infrastructure\MongoDB\UserProjectionRepository;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPassword;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPasswordRecoveryInitiated;
use WalletAccountant\Tests\Functional\FunctionalTestCase;

/**
 * UserPasswordRecoveryTest
 */
class UserPasswordRecoveryTest extends FunctionalTestCase
{
    /**
     * @throws DBALException
     */
    public function testUserPasswordRecovery(): void
    {
        $code = UserWithPasswordRecoveryInitiated::PASSWORD_RECOVERY_CODE;
        $password = 'testpassword';
        DateTime::setTestNow(DateTime::now());

        $this->loadFixtures();

        $client = static::createClient();
        $client->request(
            Request::METHOD_POST,
            sprintf('/recover-password/%s', $code),
            [
                RecoverUserPassword::PASSWORD => $password,
                RecoverUserPassword::REPEAT_PASSWORD => $password
            ]
        );

        // Make sure projection is updated with the new event
        $this->runProjection('user');

        $response = $client->getResponse();

        if (!$response instanceof Response) {
            $this->fail(sprintf('response in not an %s object', Response::class));
        }

        $this->assertTrue($client->isOkAndJson());
        $this->assertEquals('{"message":"user password recovered"}', $response->getContent());

        $userProjectionRepository = $this->container->get('test.user_projection_repository');

        $actualUser = $userProjectionRepository->getByEmail(UserWithPasswordRecoveryInitiated::EMAIL);

        $expectedStatus = new Status(false, false, false, true);

        $this->assertEquals($expectedStatus, $actualUser->getStatus());
        $this->assertFalse($actualUser->hasRecovery());

        $passwordEncoder = $this->container->get('test.password_encoder');
        $expectedPassword = $passwordEncoder->encodeUserPassword($actualUser, $password);

        $this->assertEquals($expectedPassword, $actualUser->getPassword());
    }
}
