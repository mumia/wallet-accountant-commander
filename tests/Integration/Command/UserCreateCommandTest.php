<?php

namespace WalletAccountant\Tests\Integration\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use function getenv;
use function json_encode;
use function sprintf;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use function var_dump;

/**
 * UserCreateCommandTest
 */
class UserCreateCommandTest extends KernelTestCase
{
    const EMAIL = 'valid@email.com';
    const FIRST_NAME = 'firstname';
    const LAST_NAME = 'lastname';

    /**
     * @throws DBALException
     */
    public function testExecute()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $registry = $container->get('doctrine');
        /** @var Connection $mysqlConnection */
        $mysqlConnection = $registry->getConnection($registry->getDefaultConnectionName());

        $this->assertNotExists($mysqlConnection);

        $command = $container->get('console.command.walletaccountant_command_usercreatecommand');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'email' => self::EMAIL,
                'first name' => self::FIRST_NAME,
                'last name' => self::LAST_NAME
            ]
        );

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $successMessage = sprintf('User created %s %s %s', self::EMAIL, self::FIRST_NAME, self::LAST_NAME);
        $this->assertContains($successMessage, $output);

        $this->assertExists($mysqlConnection);

        // Todo: check if there is a way to test the projections triggered by this event?
    }

    /**
     * @param Connection $connection
     *
     * @throws DBALException
     */
    private function assertNotExists(Connection $connection): void
    {
        $streams = $this->getAllStreams($connection);

        $this->assertCount(0, $streams);
    }

    /**
     * @param Connection $connection
     *
     * @throws DBALException
     */
    private function assertExists(Connection $connection): void
    {
        $streams = $this->getAllStreams($connection);

        $this->assertCount(1, $streams);

        $streamName = $streams[0]['stream_name'];

        $statement = $connection->prepare(sprintf('SELECT * FROM %s', $streamName));
        $statement->execute();

        $value = $statement->fetch();

        $expectedPayload = [
            'email' => self::EMAIL,
            'first_name' => self::FIRST_NAME,
            'last_name' => self::LAST_NAME
        ];

        $this->assertEquals(json_encode($expectedPayload), $value['payload']);
    }

    /**
     * @param Connection $connection
     *
     * @return array
     *
     * @throws DBALException
     */
    private function getAllStreams(Connection $connection): array
    {
        $statement = $connection->prepare('SELECT * FROM event_streams');
        $statement->execute();

        return $statement->fetchAll();
    }
}
