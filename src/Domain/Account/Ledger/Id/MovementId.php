<?php

namespace WalletAccountant\Domain\Account\Ledger\Id;

use Ramsey\Uuid\Uuid;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Common\Id;
use WalletAccountant\Domain\Common\ValueObjectInterface;

/**
 * MovementId
 */
class MovementId extends Id
{
    /**
     * @return MovementId
     *
     * @throws InvalidArgumentException
     */
    public static function createNew(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    /**
     * @param string $uuid
     *
     * @return MovementId
     *
     * @throws InvalidArgumentException
     */
    public static function createFromString(string $uuid): self
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
