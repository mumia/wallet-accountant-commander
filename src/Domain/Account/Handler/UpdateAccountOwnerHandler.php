<?php

namespace WalletAccountant\Domain\Account\Handler;

use function sprintf;
use WalletAccountant\Common\Exceptions\Account\AccountAggregateNotFoundException;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\AccountRepositoryInterface;
use WalletAccountant\Domain\Account\Command\UpdateAccountOwner;
use WalletAccountant\Domain\User\User;
use WalletAccountant\Domain\User\UserRepositoryInterface;

/**
 * UpdateAccountOwnerHandler
 */
final class UpdateAccountOwnerHandler
{
    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param UserRepositoryInterface    $userRepository
     */
    public function __construct(AccountRepositoryInterface $accountRepository, UserRepositoryInterface $userRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateAccountOwner $command
     *
     * @throws InvalidArgumentException
     * @throws AccountAggregateNotFoundException
     */
    public function __invoke(UpdateAccountOwner $command): void
    {
        $this->validateCommand($command);

        $account = $this->accountRepository->get($command->accountId());
        $account->setOwnerId($command->ownerId());

        $this->accountRepository->save($account);
    }

    /**
     * @param UpdateAccountOwner $command
     *
     * @throws InvalidArgumentException
     */
    private function validateCommand(UpdateAccountOwner $command): void
    {
        $user = $this->userRepository->getOrNull($command->ownerId());
        if (!$user instanceof User) {
            throw new InvalidArgumentException(
                sprintf(
                    'failed to update account owner: user(owner) with id "%s" not found',
                    $command->ownerId()->toString()
                )
            );
        }
    }
}
