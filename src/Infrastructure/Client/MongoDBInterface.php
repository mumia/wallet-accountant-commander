<?php

namespace WalletAccountant\Infrastructure\Client;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * ClientInterface
 */
interface MongoDBInterface
{
    /**
     * @return ObjectManager
     */
    public function getManager(): ObjectManager;

    /**
     * @param string $repositoryName
     *
     * @return ObjectRepository
     */
    public function getRepository(string $repositoryName): ObjectRepository;
}
