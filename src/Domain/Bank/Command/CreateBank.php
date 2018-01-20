<?php

namespace WalletAccountant\Domain\Bank\Command;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\Command;

/**
 * CreateBank
 */
class CreateBank extends Command
{
    public const ID = 'id';
    public const NAME = 'name';

    /**
     * @return BankId
     *
     * @throws InvalidArgumentException
     */
    public function bankId(): BankId
    {
        return BankId::createFromString($this->payload()[self::ID]);
    }

    /**
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function name(): string
    {
        $payload = $this->payload();

        return $payload[self::NAME];
    }
}
