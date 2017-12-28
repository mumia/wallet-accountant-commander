<?php

namespace WalletAccountant\Domain\User\Handler;

use WalletAccountant\Domain\User\Command\CreateUser;
use WalletAccountant\Domain\User\User;
use WalletAccountant\Domain\User\UserRepositoryInterface;
use WalletAccountant\Exceptions\InvalidArgumentException;

/**
 * CreateUserHandler
 */
class CreateUserHandler
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
     * @param CreateUser $command
     *
     * @throws InvalidArgumentException
     */
    public function __invoke(CreateUser $command): void
    {

        $user = User::createUser($command->userId(), $command->email(), $command->name());

        $this->userRepository->save($user);
    }
}
