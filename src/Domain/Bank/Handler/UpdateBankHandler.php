<?php

namespace WalletAccountant\Domain\Bank\Handler;

use function var_dump;
use WalletAccountant\Common\Exceptions\Bank\BankAggregateNotFoundException;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\BankRepositoryInterface;
use WalletAccountant\Domain\Bank\Command\UpdateBank;

/**
 * UpdateBankHandler
 */
final class UpdateBankHandler
{
    /**
     * @var BankRepositoryInterface
     */
    private $bankRepository;

    /**
     * @param BankRepositoryInterface $bankRepository
     */
    public function __construct(BankRepositoryInterface $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    /**
     * @param UpdateBank $command
     *
     * @throws InvalidArgumentException
     * @throws BankAggregateNotFoundException
     */
    public function __invoke(UpdateBank $command): void
    {
        $bank = $this->bankRepository->get($command->bankId());
        $bank->setName($command->name());

        $this->bankRepository->save($bank);
    }
}
