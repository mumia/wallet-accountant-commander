<?php

namespace WalletAccountant\Tests\Functional\Fixtures\Bank;

use Doctrine\DBAL\Connection;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use WalletAccountant\Domain\Bank\Bank as BankDomain;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Bank\Name\Name as NameDomain;
use WalletAccountant\Projection\ProjectionRunner;
use WalletAccountant\Tests\Functional\Fixtures\AbstractFixtures;

class BankFixtures extends AbstractFixtures
{
    public const ID = '381cbd9b-7bd8-4fd0-8200-cbd967c11986';
    public const NAME = 'Bank name';

    /**
     * @var BankProjectionRepositoryInterface
     */
    private $bankProjectionRepository;

    /**
     * BankFixtures constructor.
     * @param AggregateRepository $aggregateRepository
     * @param ProjectionRunner $projectionRunner
     * @param Connection $dbalConnection
     * @param BankProjectionRepositoryInterface $bankProjectionRepository
     */
    public function __construct(
        AggregateRepository $aggregateRepository,
        ProjectionRunner $projectionRunner,
        Connection $dbalConnection,
        BankProjectionRepositoryInterface $bankProjectionRepository
    ) {
        parent::__construct($aggregateRepository, $projectionRunner, $dbalConnection);

        $this->bankProjectionRepository = $bankProjectionRepository;
    }

    public function createBank() : BankDomain {
        $bankDomain = BankDomain::createBank(
            BankId::createFromString(self::ID),
            new NameDomain(self::NAME)
        );

        $this->runEvents($bankDomain);

        return $bankDomain;
    }

    /**
     * @return BankProjectionRepositoryInterface
     */
    public function getBankProjectionRepository(): BankProjectionRepositoryInterface
    {
        return $this->bankProjectionRepository;
    }
}
