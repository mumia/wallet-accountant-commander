<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Common\Authored;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * Bank
 *
 * @MongoDB\Document
 */
final class Bank
{
    /**
     * @var BankId
     *
     * @MongoDB\Id(strategy="none", type="bankid")
     */
    private $id;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    private $name;

    /**
     * @var Authored
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\Common\Authored")
     */
    private $created;

    /**
     * @var Authored
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\Common\Authored")
     */
    private $updated;

    /**
     * @param BankId   $id
     * @param string   $name
     * @param Authored $created
     * @param Authored $updated
     */
    public function __construct(BankId $id, string $name, Authored $created, Authored $updated)
    {
        $this->id = $id;
        $this->name = $name;
        $this->created = $created;
        $this->updated = $updated;
    }

    /**
     * @return BankId
     *
     * @throws InvalidArgumentException
     */
    public function id(): BankId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Authored
     */
    public function created(): Authored
    {
        return $this->created;
    }

    /**
     * @return Authored
     */
    public function updated(): Authored
    {
        return $this->updated;
    }
}
