<?php

namespace WalletAccountant\Tests\Integration;

use Doctrine\Common\Persistence\ObjectRepository;
use Exception;
use function sprintf;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use WalletAccountant\Document\User;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * IntegrationTestCase
 */
abstract class IntegrationTestCase extends KernelTestCase
{
    /**
     * @var Connection
     */
    protected $eventStreamConnection;

    /**
     * @var ObjectRepository
     */
    protected $projectionRepository;

    /**
     * IntegrationTestCase constructor.
     * @param string $projectionDocumentName
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     *
     * @throws Exception
     */
    public function __construct(
        string $projectionDocumentName,
        string $name = null,
        array $data = [],
        string $dataName = ''
    ) {
        parent::__construct($name, $data, $dataName);

        self::bootKernel();

        $this->eventStreamConnection = $this->getEventStreamDatabaseConnection();

        $this->projectionRepository = $this->getProjectionRepository($projectionDocumentName);
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
     * @return Connection
     */
    protected function getEventStreamDatabaseConnection(): Connection
    {
        $registry = static::$container->get('doctrine');

        return $registry->getConnection($registry->getDefaultConnectionName());
    }

    /**
     * @param string $class
     * @return ObjectRepository
     * @throws Exception
     */
    protected function getProjectionRepository(string $class): ObjectRepository
    {
        $registry = static::$container->get('doctrine_mongodb');

        return $registry->getRepository($class);
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
     * @param string $projectionServiceName
     * @throws Exception
     */
    protected function runProjection(string $projectionServiceName): void
    {
        $projectionRunner = static::$container->get($projectionServiceName);
        $projectionRunner->run();
    }
}
