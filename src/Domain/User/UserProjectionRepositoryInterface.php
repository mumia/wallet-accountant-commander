<?php

namespace WalletAccountant\Domain\User;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\User as UserDocument;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * UserProjectionRepositoryInterface
 */
interface UserProjectionRepositoryInterface
{
    /**
     * @param UserDocument      $newDocument
     * @param null|UserDocument $oldDocument
     *
     * @throws InvalidArgumentException
     */
    public function persist(UserDocument $newDocument, ?UserDocument $oldDocument): void;

    /**
     * @param Email $email
     *
     * @return bool
     */
    public function emailExists(Email $email): bool;

    /**
     * @param Email $email
     *
     * @return null|UserDocument
     */
    public function getByEmailOrNull(Email $email): ?UserDocument;

    /**
     * @param Email $email
     *
     * @return UserDocument
     *
     * @throws UserNotFoundException
     */
    public function getByEmail(Email $email): UserDocument;

    /**
     * @param UserId $id
     *
     * @return null|UserDocument
     */
    public function getByIdOrNull(UserId $id): ?UserDocument;

    /**
     * @param UserId $id
     *
     * @return UserDocument
     *
     * @throws UserNotFoundException
     */
    public function getById(UserId $id): UserDocument;

    /**
     * @param string $passwordRecoveryCode
     *
     * @return UserDocument
     *
     * @throws UserNotFoundException
     */
    public function getByPasswordRecoveryCode(string $passwordRecoveryCode): UserDocument;
}
