<?php

namespace WalletAccountant\Domain\Account\Event;

use Prooph\EventSourcing\AggregateChanged;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Account\Ledger\Id\MovementId;
use WalletAccountant\Domain\Account\Ledger\Movement;
use WalletAccountant\Domain\Account\Ledger\Type\Type;
use WalletAccountant\Domain\Common\Money;
use WalletAccountant\Domain\Common\MoneyZeroOrPositive;

/**
 * MovementAddedToLedger
 */
class MovementAddedToLedger extends AggregateChanged
{
    private const MOVEMENT_ID = 'movement_id';
    private const TYPE = 'type';
    private const AMOUNT = 'amount';
    private const CURRENCY_CODE = 'currency_code';
    private const DESCRIPTION = 'description';
    private const PROCESSED_AT = 'processed_at';

    /**
     * @param AccountId $id
     * @param Movement  $movement
     */
    public function __construct(AccountId $id, Movement $movement)
    {
        parent::__construct(
            $id,
            [
                self::MOVEMENT_ID => $movement->getId()->toString(),
                self::TYPE => $movement->getType()->toString(),
                self::AMOUNT => $movement->getValue()->getAmount(),
                self::CURRENCY_CODE => $movement->getValue()->getCurrency()->toString(),
                self::DESCRIPTION => $movement->getDescription(),
                self::PROCESSED_AT => $movement->getProcessedOn()->toDateTime(),
            ]
        );
    }

    /**
     * @return AccountId
     *
     * @throws InvalidArgumentException
     */
    public function id(): AccountId
    {
        return AccountId::createFromString($this->aggregateId());
    }

    /**
     * @return Movement
     *
     * @throws InvalidArgumentException
     */
    public function movement(): Movement
    {
        return new Movement(
            MovementId::createFromString($this->payload()[self::MOVEMENT_ID]),
            Type::createFromString($this->payload()[self::TYPE]),
            MoneyZeroOrPositive::createFromAmountCurrency(
                $this->payload()[self::AMOUNT],
                $this->payload()[self::CURRENCY_CODE]
            ),
            $this->payload()[self::DESCRIPTION],
            DateTime::createFromDateTimeFormat($this->payload()[self::PROCESSED_AT])
        );
    }
}
