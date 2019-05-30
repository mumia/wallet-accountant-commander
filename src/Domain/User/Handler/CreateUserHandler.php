<?php

namespace WalletAccountant\Domain\User\Handler;

use Prooph\Common\Messaging\MessageFactory;
use WalletAccountant\Domain\User\Command\CreateUser;
use WalletAccountant\Domain\User\User;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;
use WalletAccountant\Domain\User\UserRepositoryInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserEmailNotUniqueException;

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
     * @throws UserEmailNotUniqueException
     */
    public function __invoke(CreateUser $command): void
    {
        // Validate email is unique using projection
        if ($this->userProjectionRepository->emailExists($command->email())) {
            throw new UserEmailNotUniqueException($command->email()->toString());
        }

        $user = User::createUser($command->userId(), $command->email(), $command->name());

        $user->initiatePasswordRecovery();

        $this->userRepository->save($user);
    }
}
