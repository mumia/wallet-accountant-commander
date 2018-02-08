<?php

namespace WalletAccountant\Projection\User;

use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\User;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Infrastructure\MongoDB\DroppableRepositoryInterface;
use WalletAccountant\Infrastructure\MongoDB\UserProjectionRepository;
use WalletAccountant\Projection\AbstractMongoDBReadModel;

/**
 * UserReadModel
 */
final class UserReadModel extends AbstractMongoDBReadModel
{
    /**
     * @var UserProjectionRepository
     */
    private $userProjectionRepository;

    /**
     * @param UserProjectionRepository $userProjectionRepository
     */
    public function __construct(UserProjectionRepository $userProjectionRepository)
    {
        $this->userProjectionRepository = $userProjectionRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): DroppableRepositoryInterface
    {
        return $this->userProjectionRepository;
    }

    /**
     * @param User $user
     *
     * @throws InvalidArgumentException
     */
    public function insert(User $user): void
    {
        $this->userProjectionRepository->persist($user, null);
    }

    /**
     * @param UserId   $id
     * @param string   $code
     * @param DateTime $expiresOn
     *
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function passwordRecovery(UserId $id, string $code, DateTime $expiresOn): void
    {
        $user = $this->userProjectionRepository->getByIdOrNull($id);

        if (!$user instanceof User) {
            throw UserNotFoundException::withId($id);
        }

        $user->initiatePasswordRecovery($code, $expiresOn);

        $this->userProjectionRepository->persist($user, null);
    }

    /**
     * @param UserId $id
     * @param string $password
     *
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function passwordRecovered(UserId $id, string $password): void
    {
        $user = $this->userProjectionRepository->getById($id);

        $user->recoverPassword($password);

        $this->userProjectionRepository->persist($user, null);
    }
}
