<?php

namespace WalletAccountant\Domain\User\Id;

use WalletAccountant\Domain\Common\Id;
use WalletAccountant\Domain\Common\ValueObjectInterface;
use Ramsey\Uuid\Uuid;
use WalletAccountant\Exceptions\InvalidArgumentException;

/**
 * UserId
 */
class UserId extends Id
{
    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public static function createNew(): UserId
    {
        return new self(Uuid::uuid4()->toString());
    }

    /**
     * @param string $uuid
     *
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public static function createFromString(string $uuid): UserId
    {
        return new self($uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function sameValueAs(ValueObjectInterface $that): bool
    {
        return $that instanceof self && parent::sameValueAs($that);
    }
}
