<?php

namespace WalletAccountant\Mediator;

use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Document\User;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;

/**
 * UserMediator
 */
class UserMediator
{
    /**
     * @var UserProjectionRepositoryInterface
     */
    private $userProjectionRepository;

    /**
     * @param UserProjectionRepositoryInterface $userProjectionRepository
     */
    public function __construct(UserProjectionRepositoryInterface $userProjectionRepository)
    {
        $this->userProjectionRepository = $userProjectionRepository;
    }

    /**
     * @param UserId $userId
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getById(UserId $userId): User
    {
        return $this->userProjectionRepository->getById($userId);
    }
}
