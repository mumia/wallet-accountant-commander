<?php

namespace WalletAccountant\Domain\User\Handler;

use function sprintf;
use WalletAccountant\Domain\User\Command\CreateUser;
use WalletAccountant\Domain\User\User;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;
use WalletAccountant\Domain\User\UserRepositoryInterface;
use WalletAccountant\Exceptions\InvalidArgumentException;
use WalletAccountant\Exceptions\User\UserEmailNotUnique;

/**
 * CreateUserHandler
 */
final class CreateUserHandler
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
     * @param CreateUser $command
     *
     * @throws InvalidArgumentException
     * @throws UserEmailNotUnique
     */
    public function __invoke(CreateUser $command): void
    {
        // Validate email is unique using projection
        if ($this->userProjectionRepository->emailExists($command->email()->toString())) {
            throw new UserEmailNotUnique(sprintf('User with email "%s" already exists', $command->email()));
        }

        $user = User::createUser($command->userId(), $command->email(), $command->name());

        $this->userRepository->save($user);
    }
}
