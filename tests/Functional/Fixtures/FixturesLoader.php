<?php

namespace WalletAccountant\Tests\Functional\Fixtures;

use Doctrine\DBAL\Connection as DBALConnection;
use Doctrine\DBAL\DBALException;

/**
 * FixturesLoader
 */
class FixturesLoader
{
    /**
     * @var DBALConnection
     */
    private $dbalConnection;

    /**
     * @var FixturesRegistry
     */
    private $fixturesRegistry;

    /**
     * @param DBALConnection   $dbalConnection
     * @param FixturesRegistry $fixturesRegistry
     */
    public function __construct(DBALConnection $dbalConnection, FixturesRegistry $fixturesRegistry)
    {
        $this->dbalConnection = $dbalConnection;
        $this->fixturesRegistry = $fixturesRegistry;
    }

    /**
     * @throws DBALException
     */
    public function loadAll(): void
    {
        $this->resetDatabase();

        foreach ($this->fixturesRegistry->getKeys() as $key) {
            $fixture = $this->fixturesRegistry->get($key);

            $eventStore = $fixture->getEventStore();
            $eventStore->create($fixture->getStream());

            $fixture->getProjectionRunner()->run();
        }
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
}
