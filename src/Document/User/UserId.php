<?php

namespace WalletAccountant\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Domain\Common\Id;
use WalletAccountant\Domain\Common\ValueObjectInterface;
use Ramsey\Uuid\Uuid;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\User\Id\UserId as UserIdDomain;

/**
 * UserId
 *
 * @MongoDB\EmbeddedDocument
 */
class UserId extends Id
{
    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    protected $id;

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
     * @param UserIdDomain $userId
     *
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public static function createFromUserIdDomain(UserIdDomain $userId): UserId
    {
        return new self($userId->toString());
    }

    /**
     * {@inheritdoc}
     */
    public function sameValueAs(ValueObjectInterface $that): bool
    {
        return $that instanceof self && parent::sameValueAs($that);
    }
}
