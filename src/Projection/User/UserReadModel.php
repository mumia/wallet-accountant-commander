<?php

namespace WalletAccountant\Projection\User;

use Prooph\EventStore\Projection\AbstractReadModel;
use function var_dump;
use WalletAccountant\Document\User;
use InvalidArgumentException;
use WalletAccountant\Infrastructure\MongoDB\UserRepository;

/**
 * UserReadModel
 */
final class UserReadModel extends AbstractReadModel
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function init(): void
    {
        // MongoDB collection will be created automatically
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        // MongoDB collection will be initialized automatically

        return true;
    }

    public function reset(): void
    {
        $this->userRepository->dropCollection();
    }

    public function delete(): void
    {
        $this->userRepository->dropCollection();
    }

    /**
     * @param User $user
     *
     * @throws InvalidArgumentException
     */
    public function insert(User $user): void
    {
        $this->userRepository->persist($user);
    }
}
