<?php

namespace WalletAccountant\Tests\Integration\User;

use Doctrine\Common\Persistence\ObjectRepository;
use function sprintf;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use WalletAccountant\Document\User;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * UserIntegrationTestCase
 */
abstract class UserIntegrationTestCase extends KernelTestCase
{
    protected const EMAIL = 'valid@email.com';
    protected const FIRST_NAME = 'firstname';
    protected const LAST_NAME = 'lastname';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Connection
     */
    protected $eventStreamConnection;

    /**
     * @var ObjectRepository
     */
    protected $projectionRepository;

    /**
     * @param string|null $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();

        $this->eventStreamConnection = $this->getEventStreamDatabaseConnection();

        $this->projectionRepository = $this->getProjectionRepository();
    }

    /**
     * @throws DBALException
     */
    protected function assertNotExists(): void
    {
        $streams = $this->getAllStreams();

        $this->assertCount(0, $streams);
    }

    /**
     * @param User $expectedProjection
     */
    protected function assertProjectionIsExpected(User $expectedProjection): void
    {
        $actualProjection = $this->projectionRepository->find(self::EMAIL);
        $this->assertEquals($expectedProjection, $actualProjection);
    }

    /**
     * @return Connection
     */
    protected function getEventStreamDatabaseConnection(): Connection
    {
        $registry = $this->container->get('doctrine');

        return $registry->getConnection($registry->getDefaultConnectionName());
    }

    /**
     * @return ObjectRepository
     */
    protected function getProjectionRepository(): ObjectRepository
    {
        $registry = $this->container->get('doctrine_mongodb');

        return $registry->getRepository(User::class);//getConnection($registry->getDefaultConnectionName());
    }

    /**
     * @return array
     *
     * @throws DBALException
     */
    protected function getAllStreams(): array
    {
        $statement = $this->eventStreamConnection->prepare('SELECT * FROM event_streams');
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param string $aggregateId
     *
     * @return array
     *
     * @throws DBALException
     */
    protected function getStreamEvents(string $aggregateId): array
    {
        $statement = $this->eventStreamConnection->prepare('SELECT * FROM event_streams WHERE real_stream_name=?');
        $statement->bindValue(1, sprintf('user-%s', $aggregateId));
        $statement->execute();

        $stream = $statement->fetch();

        $statement = $this->eventStreamConnection->prepare(sprintf('SELECT * FROM %s', $stream['stream_name']));
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param string $aggregateId
     * @param int    $version
     *
     * @return array
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    protected function getEventVersion(string $aggregateId, int $version): array
    {
        $streamEvents = $this->getStreamEvents($aggregateId);

        foreach ($streamEvents as $event) {
            if ($event['no'] === (string)$version) {
                return $event;
            }
        }

        throw new InvalidArgumentException('Version not found');
    }

    /**
     * @return CommandTester
     */
    protected function createUser(): CommandTester
    {
        $command = $this->container->get('console.command.walletaccountant_command_usercreatecommand');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'email' => self::EMAIL,
                'first name' => self::FIRST_NAME,
                'last name' => self::LAST_NAME
            ]
        );

        return $commandTester;
    }

    protected function runProjection(): void
    {
        // Run projection
        $projectionRunner = $this->container->get('walletaccountant.projection_runner.user');
        $projectionRunner->run();
    }
}
