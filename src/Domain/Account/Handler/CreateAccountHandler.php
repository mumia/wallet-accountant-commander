<?php

namespace WalletAccountant\Domain\Account\Handler;

use function sprintf;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Account;
use WalletAccountant\Domain\Account\AccountRepositoryInterface;
use WalletAccountant\Domain\Account\Command\CreateAccount;
use WalletAccountant\Domain\Bank\Bank;
use WalletAccountant\Domain\Bank\BankRepositoryInterface;
use WalletAccountant\Domain\User\User;
use WalletAccountant\Domain\User\UserRepositoryInterface;

/**
 * CreateAccountHandler
 */
final class CreateAccountHandler
{
    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    /**
     * @var BankRepositoryInterface
     */
    private $bankRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param BankRepositoryInterface    $bankRepository
     * @param UserRepositoryInterface    $userRepository
     */
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        BankRepositoryInterface $bankRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->bankRepository = $bankRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param CreateAccount $command
     *
     * @throws InvalidArgumentException
     */
    public function __invoke(CreateAccount $command): void
    {
        $this->validateCommand($command);

        $account = Account::createAccount(
            $command->accountId(),
            $command->bankId(),
            $command->ownerId(),
            $command->iban()
        );

        $this->accountRepository->save($account);
    }

    /**
     * @param CreateAccount $command
     *
     * @throws InvalidArgumentException
     */
    private function validateCommand(CreateAccount $command): void
    {
        $bank = $this->bankRepository->getOrNull($command->bankId());
        if (!$bank instanceof Bank) {
            throw new InvalidArgumentException(
                sprintf('failed to create account: bank with id "%s" not found', $command->bankId()->toString())
            );
        }

        $user = $this->userRepository->getOrNull($command->ownerId());
        if (!$user instanceof User) {
            throw new InvalidArgumentException(
                sprintf('failed to create account: user(owner) with id "%s" not found', $command->ownerId()->toString())
            );
        }
    }
}
