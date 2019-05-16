<?php

namespace WalletAccountant\Domain\Common;

/**
 * ValueObjectInterface
 */
interface ValueObjectInterface
{
    /**
     * Value objects compare by the values of their attributes, they don't have an identity.
     *
     * @param ValueObjectInterface $that
     *
     * @return bool True if the given value object's and this value object's attributes are the same.
     */
    public function sameValueAs(ValueObjectInterface $that): bool;
}
