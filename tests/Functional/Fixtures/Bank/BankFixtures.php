<?php

namespace WalletAccountant\Tests\Functional\Fixtures\Bank;

use Doctrine\DBAL\Connection as DBALConnection;
use Doctrine\DBAL\DBALException;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use WalletAccountant\Document\Bank;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Domain\Bank\Bank as BankDomain;
use WalletAccountant\Domain\Bank\BankRepositoryInterface;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Projection\ProjectionRunner;
use WalletAccountant\Tests\Functional\Fixtures\AbstractFixtures;

/**
 * BankFixtures
 */
class BankFixtures extends AbstractFixtures
{
    public const ID = '3526d9bb-b61d-4e91-b7f3-84bd3f9d6467';
    public const BANK_NAME = 'bank_name';
    public const BANK_NAME_UPDATE = 'bank_name_updated';

    /**
     * @var BankRepositoryInterface
     */
    private $bankRepository;

    /**
     * @var BankProjectionRepositoryInterface
     */
    private $bankProjectionRepository;

    /**
     * @param AggregateRepository               $aggregateRepository
     * @param ProjectionRunner                  $projectionRunner
     * @param DBALConnection                    $dbalConnection
     * @param BankProjectionRepositoryInterface $bankProjectionRepository
     */
    public function __construct(
        AggregateRepository $aggregateRepository,
        ProjectionRunner $projectionRunner,
        DBALConnection $dbalConnection,
        BankProjectionRepositoryInterface $bankProjectionRepository
    ) {
        parent::__construct($aggregateRepository, $projectionRunner, $dbalConnection);

        $this->bankRepository = $aggregateRepository;
        $this->bankProjectionRepository = $bankProjectionRepository;
    }

    /**
     * @throws DBALException
     */
    public function createBank(): void
    {
        $this->resetDatabase();

        $bankDomain = BankDomain::createBank(BankId::createFromString(self::ID), self::BANK_NAME);

        $this->runEvents($bankDomain);
    }

    public function updateBank(): void
    {
        $bankDomain = $this->bankRepository->get(BankId::createFromString(self::ID));

        $bankDomain->setName(self::BANK_NAME_UPDATE);

        $this->runEvents($bankDomain);
    }

    /**
     * @return Bank
     */
    public function getCurrentBank(): Bank
    {
        return $this->bankProjectionRepository->getByAggregateId(self::ID);
    }
}
