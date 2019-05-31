<?php

namespace WalletAccountant\Domain\Common;

use Prooph\EventSourcing\AggregateChanged;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\MetadataEnricher\CreatedByMetadataEnricher;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * AbstractAggregateChanged
 */
abstract class AbstractAggregateChanged extends AggregateChanged
{
    /**
     * @param string $aggregateId
     * @param array  $payload
     * @param array  $metadata
     */
    public function __construct(string $aggregateId, array $payload, array $metadata = [])
    {
        $this->createdAt = DateTime::now();

        parent::__construct($aggregateId, $payload, $metadata);
    }

    /**
     * @return bool
     */
    public function hasCreatedBy(): bool
    {
        return isset($this->metadata()[CreatedByMetadataEnricher::METADATA_CREATED_BY]);
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function getCreatedBy(): UserId
    {
        return UserId::createFromString($this->metadata()[CreatedByMetadataEnricher::METADATA_CREATED_BY]);
    }
}
