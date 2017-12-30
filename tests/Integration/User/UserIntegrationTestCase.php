<?php

namespace WalletAccountant\Tests\Integration\User;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\MongoDB\Connection as MongoDBConnection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\DependencyInjection\Container;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;

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
     * @var MongoDBConnection
     */
    protected $projectionConnection;

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

        $this->projectionConnection = $this->getProjectionDatabaseConnection();
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
     * @throws DBALException
     */
    protected function assertExists(): void
    {
        $streams = $this->getAllStreams();

        $this->assertCount(1, $streams);

        $streamName = $streams[0]['stream_name'];

        $statement = $this->eventStreamConnection->prepare(sprintf('SELECT * FROM %s', $streamName));
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
     * @param array $expectedProjection
     */
    protected function assertProjectionIsExpected(array $expectedProjection): void
    {
        $collection = $this->projectionConnection->selectCollection('walletaccountant_test', 'User');
        $actualProjection = $collection->findOne(['_id' => self::EMAIL]);

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
     * @return Connection
     */
    protected function getProjectionDatabaseConnection(): MongoDBConnection
    {
        $registry = $this->container->get('doctrine_mongodb');

        return $registry->getConnection($registry->getDefaultConnectionName());
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
}
