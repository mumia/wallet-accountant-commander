<?php

namespace WalletAccountant\Projection;

use Prooph\EventStore\Projection\AbstractReadModel;
use WalletAccountant\Infrastructure\MongoDB\DroppableRepositoryInterface;

/**
 * AbstractMongoDBReadModel
 */
abstract class AbstractMongoDBReadModel extends AbstractReadModel
{
    public function init(): void
    {
        // MongoDB collection will be created automatically
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        // MongoDB collection will be initialized automatically

        return true;
    }

    public function reset(): void
    {
        $this->getRepository()->dropCollection();
    }

    public function delete(): void
    {
        $this->getRepository()->dropCollection();
    }

    /**
     * @return DroppableRepositoryInterface
     */
    abstract public function getRepository(): DroppableRepositoryInterface;
}
