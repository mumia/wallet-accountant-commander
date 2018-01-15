<?php

namespace WalletAccountant\Tests\Functional\Fixtures;

use Doctrine\DBAL\Connection as DBALConnection;
use Doctrine\DBAL\DBALException;
use Doctrine\MongoDB\Connection as MongoDBConnection;
use Prooph\Bundle\ServiceBus\EventBus;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\AggregateRoot;
use WalletAccountant\Projection\ProjectionRunner;

/**
 * AbstractFixtures
 */
class AbstractFixtures
{
    /**
     * @var AggregateRepository
     */
    protected $aggregateRepository;

    /**
     * @var ProjectionRunner
     */
    protected $projectionRunner;

    /**
     * @var DBALConnection
     */
    protected $dbalConnection;

    /**
     * @param AggregateRepository $aggregateRepository
     * @param ProjectionRunner    $projectionRunner
     * @param DBALConnection      $dbalConnection
     */
    public function __construct(
        AggregateRepository $aggregateRepository,
        ProjectionRunner $projectionRunner,
        DBALConnection $dbalConnection
    ) {
        $this->aggregateRepository = $aggregateRepository;
        $this->projectionRunner = $projectionRunner;
        $this->dbalConnection = $dbalConnection;
    }

    /**
     * @throws DBALException
     */
    protected function resetDatabase(): void
    {
        $streams = $this->dbalConnection->fetchAll('SELECT stream_name FROM event_streams');
        foreach ($streams as $stream) {
            $this->dbalConnection->executeQuery(sprintf('DROP TABLE IF EXISTS %s', $stream['stream_name']));
        }

        $this->dbalConnection->executeQuery('TRUNCATE event_streams');
        $this->dbalConnection->executeQuery('TRUNCATE projections');
    }

    /**
     * @param AggregateRoot $aggregateRoot
     */
    protected function runEvents(AggregateRoot $aggregateRoot): void
    {
        $this->aggregateRepository->saveAggregateRoot($aggregateRoot);

        $this->projectionRunner->run();
    }
}
