<?php

namespace WalletAccountant\Domain\Account\Handler;

use WalletAccountant\Common\Exceptions\Account\AccountAggregateNotFoundException;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\AccountRepositoryInterface;
use WalletAccountant\Domain\Account\Command\AddMovementToLedger;
use WalletAccountant\Domain\Account\Ledger\Movement;

/**
 * AddMovementToLedgerHandler
 */
final class AddMovementToLedgerHandler
{
    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    /**
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param AddMovementToLedger $command
     *
     * @throws InvalidArgumentException
     * @throws AccountAggregateNotFoundException
     */
    public function __invoke(AddMovementToLedger $command): void
    {
        $account = $this->accountRepository->get($command->accountId());

        $movement = new Movement(
            $command->movementId(),
            $command->type(),
            $command->value(),
            $command->description(),
            $command->processedOn()
        );

        $account->addMovementToLedger($movement);

        $this->accountRepository->save($account);
    }
}
