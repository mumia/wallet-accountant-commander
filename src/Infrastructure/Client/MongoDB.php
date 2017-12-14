<?php

namespace WalletAccountant\Infrastructure\Client;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Client
 */
class MongoDB implements MongoDBInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getManager(): ObjectManager
    {
        return $this->registry->getManager();
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(string $repositoryName): ObjectRepository
    {
        return $this->registry->getRepository($repositoryName);
    }
}
