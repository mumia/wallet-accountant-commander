<?php

namespace WalletAccountant\Domain\Account\Command;

use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Account\Ledger\Id\MovementId;
use WalletAccountant\Domain\Account\Ledger\Type\Type;
use WalletAccountant\Domain\Common\AbstractCommand;
use WalletAccountant\Domain\Common\CurrencyCode;
use WalletAccountant\Domain\Common\Money;
use WalletAccountant\Domain\Common\MoneyZeroOrPositive;

/**
 * AddMovementToLedger
 */
class AddMovementToLedger extends AbstractCommand
{
    public const ACCOUNT_ID = 'account_id';
    public const ID = 'id';
    public const TYPE = 'type';
    public const AMOUNT = 'amount';
    public const DESCRIPTION = 'description';
    public const PROCESSED_ON = 'processed_on';

    /**
     * @return AccountId
     *
     * @throws InvalidArgumentException
     */
    public function accountId(): AccountId
    {
        return new AccountId($this->payload()[self::ACCOUNT_ID]);
    }

    /**
     * @return MovementId
     *
     * @throws InvalidArgumentException
     */
    public function movementId(): MovementId
    {
        return new MovementId($this->payload()[self::ID]);
    }

    /**
     * @return Type
     *
     * @throws InvalidArgumentException
     */
    public function type(): Type
    {
        return new Type($this->payload()[self::TYPE]);
    }

    /**
     * @return MoneyZeroOrPositive
     *
     * @throws InvalidArgumentException
     */
    public function value(): MoneyZeroOrPositive
    {
        return new MoneyZeroOrPositive($this->payload()[self::AMOUNT], CurrencyCode::createEuro());
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->payload()[self::DESCRIPTION];
    }

    /**
     * @return DateTime
     *
     * @throws InvalidArgumentException
     */
    public function processedOn(): DateTime
    {
        return DateTime::createFromDateTimeFormat($this->payload()[self::PROCESSED_ON]);
    }
}
