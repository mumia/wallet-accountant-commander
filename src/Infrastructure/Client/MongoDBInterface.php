<?php

namespace WalletAccountant\Infrastructure\Client;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\MongoDB\Connection;
use InvalidArgumentException;

/**
 * ClientInterface
 */
interface MongoDBInterface
{
    /**
     * @return ObjectManager
     *
     * @throws InvalidArgumentException
     */
    public function getManager(): ObjectManager;

    /**
     * @param string $repositoryName
     *
     * @return ObjectRepository
     */
    public function getRepository(string $repositoryName): ObjectRepository;

    /**
     * @return Connection
     */
    public function getConnection(): Connection;
}
