<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use WalletAccountant\Infrastructure\Client\MongoDB;

/**
 * AbstractMongoDBRepository
 */
abstract class AbstractMongoDBRepository
{
    /**
     * @var MongoDB
     */
    protected $client;

    /**
     * @param MongoDB $client
     */
    public function __construct(MongoDB $client)
    {
        $this->client = $client;
    }
}
