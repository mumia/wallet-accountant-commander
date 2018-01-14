<?php

namespace WalletAccountant\Domain\User\Handler;

use WalletAccountant\Common\Exceptions\User\UserAggregateNotFoundException;
use WalletAccountant\Domain\User\Command\InitiatePasswordRecovery;
use WalletAccountant\Domain\User\UserRepositoryInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * InitiatePasswordRecoveryHandler
 */
final class InitiatePasswordRecoveryHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param InitiatePasswordRecovery $command
     *
     * @throws InvalidArgumentException
     * @throws UserAggregateNotFoundException
     */
    public function __invoke(InitiatePasswordRecovery $command): void
    {
        $user = $this->userRepository->get($command->userId());

        $user->initiatePasswordRecovery();

        $this->userRepository->save($user);
    }
}
