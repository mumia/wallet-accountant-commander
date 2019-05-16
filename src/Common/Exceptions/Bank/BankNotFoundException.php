<?php

namespace WalletAccountant\Common\Exceptions\Bank;

use LogicException;
use function sprintf;

/**
 * Class BankNotFoundException
 * @package WalletAccountant\Common\Exceptions\Bank
 */
class BankNotFoundException extends LogicException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct(sprintf('bank with %s was not found', $message), 0, null);
    }

    /**
     * @param string $userId
     *
     * @return BankNotFoundException
     */
    public static function withId(string $userId): self
    {
        return new self(sprintf('id "%s"', $userId));
    }
}
