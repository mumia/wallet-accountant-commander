<?php

namespace WalletAccountant\Domain\User;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\User as UserDocument;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;

/**
 * UserProjectionRepositoryInterface
 */
interface UserProjectionRepositoryInterface
{
    public const COLLECTION_NAME = 'user';

    /**
     * @param UserDocument $document
     *
     * @throws InvalidArgumentException
     */
    public function persist(UserDocument $document): void;

    /**
     * @param string $email
     *
     * @return bool
     */
    public function emailExists(string $email): bool;

    /**
     * @param string $email
     *
     * @return null|UserDocument
     */
    public function getByEmailOrNull(string $email): ?UserDocument;

    /**
     * @param string $email
     *
     * @return UserDocument
     *
     * @throws UserNotFoundException
     */
    public function getByEmail(string $email): UserDocument;

    /**
     * @param string $aggregateId
     *
     * @return null|UserDocument
     */
    public function getByAggregateIdOrNull(string $aggregateId): ?UserDocument;

    /**
     * @param string $aggregateId
     *
     * @return UserDocument
     *
     * @throws UserNotFoundException
     */
    public function getByAggregateId(string $aggregateId): UserDocument;

    /**
     * @param string $passwordRecoveryCode
     *
     * @return UserDocument
     *
     * @throws UserNotFoundException
     */
    public function getByPasswordRecoveryCode(string $passwordRecoveryCode): UserDocument;
}
