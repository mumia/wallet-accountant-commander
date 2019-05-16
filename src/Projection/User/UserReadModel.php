<?php

namespace WalletAccountant\Projection\User;

use Prooph\EventStore\Projection\AbstractReadModel;
use function var_dump;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\User;
use WalletAccountant\Document\User\Recovery;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Infrastructure\MongoDB\UserProjectionRepository;

/**
 * UserReadModel
 */
final class UserReadModel extends AbstractReadModel
{
    /**
     * @var UserProjectionRepository
     */
    private $userRepository;

    /**
     * @param UserProjectionRepository $userRepository
     */
    public function __construct(UserProjectionRepository $userRepository)
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

    /**
     * @param string   $id
     * @param string   $code
     * @param DateTime $expiresOn
     *
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function passwordRecovery(string $id, string $code, DateTime $expiresOn): void
    {
        $user = $this->userRepository->getByAggregateIdOrNull($id);

        if (!$user instanceof User) {
            throw UserNotFoundException::withId($id);
        }

        $user->initiatePasswordRecovery($code, $expiresOn);

        $this->userRepository->persist($user);
    }

    /**
     * @param string   $id
     * @param string   $password
     *
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function passwordRecovered(string $id, string $password): void
    {
        $user = $this->userRepository->getByAggregateId($id);

        $user->recoverPassword($password);

        $this->userRepository->persist($user);
    }
}
