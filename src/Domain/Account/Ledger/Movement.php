<?php

namespace WalletAccountant\Domain\Account\Ledger;

use Respect\Validation\Validator;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Ledger\Id\MovementId;
use WalletAccountant\Domain\Account\Ledger\Type\Type;
use WalletAccountant\Domain\Common\Money;
use WalletAccountant\Domain\Common\MoneyZeroOrPositive;

/**
 * Movement
 */
class Movement
{
    /**
     * @var MovementId
     */
    private $id;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var MoneyZeroOrPositive
     */
    private $value;

    /**
     * @var string
     */
    private $description;

    /**
     * @var DateTime
     */
    private $processedOn;

    /**
     * @param MovementId          $id
     * @param Type                $type
     * @param MoneyZeroOrPositive $value
     * @param string              $description
     * @param DateTime            $processedOn
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        MovementId $id,
        Type $type,
        MoneyZeroOrPositive $value,
        string $description,
        DateTime $processedOn
    ) {
        if (!Validator::stringType()->notEmpty()->validate($description)) {
            throw new InvalidArgumentException('no movement description supplied');
        }

        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
        $this->description = $description;
        $this->processedOn = $processedOn;
    }

    /**
     * @return MovementId
     */
    public function getId(): MovementId
    {
        return $this->id;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return MoneyZeroOrPositive
     */
    public function getValue(): MoneyZeroOrPositive
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return DateTime
     */
    public function getProcessedOn(): DateTime
    {
        return $this->processedOn;
    }

    /**
     * @param Money $balance
     *
     * @return Money
     *
     * @throws InvalidArgumentException
     */
    public function calculateNewBalance(Money $balance): Money
    {
        $amount = $balance->getAmount();

        if ($this->type->isDebit()) {
            $amount -= $this->value->getAmount();
        } else {
            $amount += $this->value->getAmount();
        }

        return new Money($amount, $balance->getCurrency());
    }
}
