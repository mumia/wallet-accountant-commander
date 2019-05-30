<?php

namespace WalletAccountant\Domain\Bank\Handler;

use WalletAccountant\Domain\Bank\Bank;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Domain\Bank\BankRepositoryInterface;
use WalletAccountant\Domain\Bank\Command\CreateBank;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserEmailNotUniqueException;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * Class CreateBankHandler
 * @package WalletAccountant\Domain\Bank\Handler
 */
final class CreateBankHandler
{
    /**
     * @var BankRepositoryInterface
     */
    private $bankRepository;

    /**
     * @var BankProjectionRepositoryInterface
     */
    private $bankProjectionRepository;

    public function __construct(
        BankRepositoryInterface $bankRepository,
        BankProjectionRepositoryInterface $bankProjectionRepository
    ) {
        $this->bankRepository = $bankRepository;
        $this->bankProjectionRepository = $bankProjectionRepository;
    }

    /**
     * @param CreateBank $command
     *
     * @throws InvalidArgumentException
     * @throws UserEmailNotUniqueException
     */
    public function __invoke(CreateBank $command): void
    {
        $bank = Bank::createBank($command->bankId(), $command->name());

        $this->bankRepository->save($bank);
    }
}
