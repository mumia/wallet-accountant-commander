<?php

namespace WalletAccountant\Tests\Functional\User;

use Doctrine\DBAL\DBALException;
use function sprintf;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Document\User\Name;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPassword;
use WalletAccountant\Tests\Functional\FunctionalTestCase;

/**
 * UserChangeNameTest
 */
class UserChangeNameTest extends FunctionalTestCase
{
    /**
     * @throws DBALException
     */
    public function testUserChangeName(): void
    {
        $firstName = 'new_first_name';
        $lastName = 'new_last_name';
        DateTime::setTestNow(DateTime::now());

        $this->loadFixtures();
        $response = $this->login(UserWithPassword::EMAIL, UserWithPassword::PASSWORD);

        $client = static::createClient();
        $client->setAuthorizationTokenFromResponse($response);
        $client->put(
            sprintf('/users/%s/name', UserWithPassword::EVENT_AGGREGATE_ID),
            [
                UserWithPassword::FIRST_NAME => $firstName,
                UserWithPassword::LAST_NAME => $lastName
            ]
        );

        // Make sure projection is updated with the new event
        $this->runProjection('user');

        $response = $client->getResponse();

        $this->assertTrue($client->isOkAndJson());
        $this->assertEquals('{"message":"user name changed"}', $response->getContent());

        $userProjectionRepository = $this->container->get('test.user_projection_repository');

        $actualUser = $userProjectionRepository->getById(
            UserId::createFromString(UserWithPassword::EVENT_AGGREGATE_ID)
        );

        $this->assertEquals(new Name($firstName, $lastName), $actualUser->getName());
    }
}
