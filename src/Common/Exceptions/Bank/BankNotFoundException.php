<?php

namespace WalletAccountant\Common\Exceptions\Bank;

use LogicException;
use function sprintf;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * UserNotFoundException
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
     * @param BankId $bankId
     *
     * @return BankNotFoundException
     */
    public static function withId(BankId $bankId): self
    {
        return new self(sprintf('id "%s"', $bankId->toString()));
    }
}
