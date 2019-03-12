<?php

namespace WalletAccountant\Document\Account;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\Common\Money;
use WalletAccountant\Domain\Account\Ledger\Id\MovementId;
use WalletAccountant\Domain\Account\Ledger\Movement as MovementDomain;
use WalletAccountant\Domain\Account\Ledger\Type\Type;

/**
 * Movement
 *
 * @MongoDB\EmbeddedDocument
 */
class Movement
{
    /**
     * @var MovementId
     *
     * @MongoDB\Field(type="movementid")
     */
    private $id;

    /**
     * @var Type
     *
     * @MongoDB\Field(type="movementtype")
     */
    private $type;

    /**
     * @var Money
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\Common\Money")
     */
    private $value;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    private $description;

    /**
     * @var DateTime
     *
     * @MongoDB\Field(type="datetime", name="processed_on")
     */
    private $processedOn;

    /**
     * @param MovementId $id
     * @param Type       $type
     * @param Money      $value
     * @param string     $description
     * @param DateTime   $processedOn
     */
    public function __construct(MovementId $id, Type $type, Money $value, string $description, DateTime $processedOn)
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
        $this->description = $description;
        $this->processedOn = $processedOn;
    }

    /**
     * @param MovementDomain $domain
     *
     * @return Movement
     */
    public static function createFromDomain(MovementDomain $domain): self
    {
        return new self(
            $domain->getId(),
            $domain->getType(),
            Money::createFromDomain($domain->getValue()),
            $domain->getDescription(),
            $domain->getProcessedOn()
        );
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
     * @return Money
     */
    public function getValue(): Money
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
}
