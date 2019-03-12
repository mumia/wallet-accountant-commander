<?php

namespace WalletAccountant\Domain\Bank\Handler;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Bank;
use WalletAccountant\Domain\Bank\BankRepositoryInterface;
use WalletAccountant\Domain\Bank\Command\CreateBank;

/**
 * CreateBankHandler
 */
final class CreateBankHandler
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
     * @param CreateBank $command
     *
     * @throws InvalidArgumentException
     */
    public function __invoke(CreateBank $command): void
    {
        $bank = Bank::createBank($command->bankId(), $command->name());

        $this->bankRepository->save($bank);
    }
}
