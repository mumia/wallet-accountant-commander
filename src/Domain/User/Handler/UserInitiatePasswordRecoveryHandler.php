<?php

namespace WalletAccountant\Domain\User\Handler;

use WalletAccountant\Common\Exceptions\User\UserAggregateNotFoundException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Domain\User\Command\UserInitiatePasswordRecovery;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;
use WalletAccountant\Domain\User\UserRepositoryInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * UserInitiatePasswordRecoveryHandler
 */
final class UserInitiatePasswordRecoveryHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserProjectionRepositoryInterface
     */
    private $userProjectionRepository;

    /**
     * @param UserRepositoryInterface           $userRepository
     * @param UserProjectionRepositoryInterface $userProjectionRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserProjectionRepositoryInterface $userProjectionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userProjectionRepository = $userProjectionRepository;
    }

    /**
     * @param UserInitiatePasswordRecovery $command
     *
     * @throws InvalidArgumentException
     * @throws UserAggregateNotFoundException
     * @throws UserNotFoundException
     */
    public function __invoke(UserInitiatePasswordRecovery $command): void
    {
        $user = $this->userProjectionRepository->getByEmail($command->email());

        $userDomain = $this->userRepository->get($user->id());

        $userDomain->initiatePasswordRecovery();

        $this->userRepository->save($userDomain);
    }
}
