<?php

namespace WalletAccountant\Tests\Integration\User;

use function get_class;
use function json_decode;
use function sprintf;
use Exception;
use Doctrine\DBAL\DBALException;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\User;
use WalletAccountant\Document\User\Name;
use WalletAccountant\Document\User\Status;
use WalletAccountant\Document\User\Recovery;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Event\UserPasswordRecoveryInitiated;
use WalletAccountant\Common\Exceptions\User\UserEmailNotUniqueException;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * UserCreateTest
 */
class UserCreateTest extends UserIntegrationTestCase
{
    /**
     * @throws DBALException
     * @throws Exception
     */
    public function testCreate()
    {
        DateTime::setTestNow(DateTime::now());

        $this->assertNotExists();

        $commandTester = $this->createUser();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $expectedSuccessMessage = sprintf('User created %s %s %s', self::EMAIL, self::FIRST_NAME, self::LAST_NAME);
        $this->assertStringContainsString($expectedSuccessMessage, $output);

        list($aggregateId, $salt) = $this->assertExistsAndReturnAggregateId();
        $event = $this->getEventVersion($aggregateId, 2);
        $this->assertEquals(UserPasswordRecoveryInitiated::class, $event['event_name']);
        $payload = json_decode($event['payload'], true);

        $this->runProjection(self::PROJECTION_NAME);

        $userId = UserId::createFromString($aggregateId);

        $this->assertProjectionIsExpected(
            $userId,
            new User(
                $userId,
                Email::createFromString(self::EMAIL),
                new Name('firstname', 'lastname'),
                [],
                '',
                $salt,
                new Status(false, false, true, true),
                new Recovery($payload['code'], DateTime::now()->addHours(360))
            )
        );
    }

    /**
     * @throws DBALException
     */
    public function testCreateWithExistingEmail()
    {
        $expectedException = UserEmailNotUniqueException::class;
        $expectedExceptionMessage = sprintf('User with email "%s" already exists', self::EMAIL);

        $this->assertExistsAndReturnAggregateId();

        try {
            $this->createUser();
        } catch (Exception $exception) {
            $this->assertEquals($expectedException, get_class($exception->getPrevious()));
            $this->assertEquals($expectedExceptionMessage, $exception->getPrevious()->getMessage());

            return;
        }

        $this->fail('Should have thrown an exception ');
    }

    /**
     * @return array Aggregate id, salt
     *
     * @throws DBALException
     */
    protected function assertExistsAndReturnAggregateId(): array
    {
        $streams = $this->getAllStreams();

        $this->assertCount(1, $streams);

        $streamName = $streams[0]['stream_name'];

        $statement = $this->eventStreamConnection->prepare(sprintf('SELECT * FROM %s', $streamName));
        $statement->execute();

        $value = $statement->fetch();
        $payload = json_decode($value['payload'], true);
        $metadata = json_decode($value['metadata'], true);

        $this->assertNotSame('', $payload['salt']);

        $expectedPayload = [
            'email' => self::EMAIL,
            'first_name' => self::FIRST_NAME,
            'last_name' => self::LAST_NAME,
            'password' => '',
            'salt' => $payload['salt'], // Ignored field, it is randomly generated
            'roles' => [],
            'status' => [
                'account_expired' => false,
                'account_locked' => false,
                'credentials_expired' => true,
                'enabled' => true
            ]
        ];

        $this->assertEquals($expectedPayload, $payload);

        return [$metadata['_aggregate_id'], $payload['salt']];
    }
}
