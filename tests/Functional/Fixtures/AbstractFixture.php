<?php

namespace WalletAccountant\Tests\Functional\Fixtures;

use ESO\IReflection\ReflClass;
use Iterator;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Ramsey\Uuid\Uuid;
use function sprintf;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Projection\ProjectionRunner;

/**
 * AbstractFixture
 */
abstract class AbstractFixture implements Iterator
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var ProjectionRunner
     */
    private $projectionRunner;

    /**
     * @var array
     */
    private $events;

    /**
     * @var int
     */
    private $eventsKey;

    /**
     * @param EventStore       $eventStore
     * @param ProjectionRunner $projectionRunner
     */
    public function __construct(EventStore $eventStore, ProjectionRunner $projectionRunner)
    {
        $this->eventStore = $eventStore;
        $this->projectionRunner = $projectionRunner;
    }

    /**
     * @return string
     */
    abstract protected function getCategory(): string;

    /**
     * @return string
     */
    abstract protected function getAggregateId(): string;

    /**
     * @return array
     */
    abstract protected function getEvents(): array;

    /**
     * @return EventStore
     */
    public function getEventStore(): EventStore
    {
        return $this->eventStore;
    }

    /**
     * @return ProjectionRunner
     */
    public function getProjectionRunner(): ProjectionRunner
    {
        return $this->projectionRunner;
    }

    /**
     * @return Stream
     */
    public function getStream(): Stream
    {
        $streamName = sprintf('%s-%s', $this->getCategory(), $this->getAggregateId());

        $this->events = $this->getEvents();
        $this->rewind();

        return new Stream(new StreamName($streamName), $this);
    }

    /**
     * {@inheritdoc}
     */
    public function current(): AggregateChanged
    {
        return $this->events[$this->eventsKey];
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->eventsKey++;
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int
    {
        return $this->eventsKey;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return isset($this->events[$this->eventsKey]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->eventsKey = 0;
    }

    /**
     * @param AggregateChanged $message
     * @param string           $eventId
     * @param int              $aggregateVersion
     * @param string           $aggregateType
     * @param DateTime         $createdAt
     * @param null|UserId      $createdBy
     */
    protected function enrichEventWithTestData(
        AggregateChanged $message,
        string $eventId,
        int $aggregateVersion,
        string $aggregateType,
        DateTime $createdAt,
        ?UserId $createdBy
    ): void {
        $reflection = ReflClass::create($message);

        $metadata = $reflection->getAnyPropertyValue('metadata');
        $metadata['_aggregate_version'] = $aggregateVersion;
        $metadata['_aggregate_type'] = $aggregateType;

        if ($createdBy instanceof UserId) {
            $metadata['created_by'] = $createdBy->toString();
        }

        $reflection->setAnyPropertyValue('uuid', Uuid::fromString($eventId));
        $reflection->setAnyPropertyValue('metadata', $metadata);
        $reflection->setAnyPropertyValue('createdAt', $createdAt);
    }
}
