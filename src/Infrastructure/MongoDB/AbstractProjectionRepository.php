<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use WalletAccountant\Infrastructure\Client\MongoDB;

/**
 * AbstractProjectionRepository
 */
abstract class AbstractProjectionRepository implements DroppableRepositoryInterface
{
    /**
     * @var MongoDB
     */
    protected $client;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @param MongoDB $client
     * @param string  $databaseName
     */
    public function __construct(MongoDB $client, string $databaseName)
    {
        $this->client = $client;
        $this->databaseName = $databaseName;
    }

    /**
     * @return string
     */
    abstract public function collectionName(): string;

    /**
     * @return string
     */
    public function databaseName(): string
    {
        return $this->databaseName;
    }

    public function dropCollection(): void
    {
        $this->client->getConnection()->selectCollection($this->databaseName, $this->collectionName())->drop();
    }
}
