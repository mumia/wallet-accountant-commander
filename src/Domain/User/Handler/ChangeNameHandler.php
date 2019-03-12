<?php

namespace WalletAccountant\Domain\User\Handler;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserAggregateNotFoundException;
use WalletAccountant\Common\Exceptions\User\UserEmailNotUniqueException;
use WalletAccountant\Domain\Common\AbstractCommand;
use WalletAccountant\Domain\User\Command\ChangeName;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;
use WalletAccountant\Domain\User\UserRepositoryInterface;

/**
 * ChangeNameHandler
 */
final class ChangeNameHandler
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
     * @param ChangeName $command
     *
     * @throws InvalidArgumentException
     * @throws UserAggregateNotFoundException
     */
    public function __invoke(ChangeName $command): void
    {
        $user = $this->userRepository->get($command->id());

        $user->replaceName(new Name($command->firstName(), $command->lastName()));

        $this->userRepository->save($user);
    }
}
