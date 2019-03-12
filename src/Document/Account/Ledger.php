<?php

namespace WalletAccountant\Document\Account;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ledger
 *
 * @MongoDB\EmbeddedDocument
 */
class Ledger
{
    /**
     * @var ArrayCollection
     *
     * @MongoDB\EmbedMany(targetDocument="WalletAccountant\Document\Account\Movement")
     */
    private $movements;

    /**
     * @param array $movements
     */
    public function __construct(array $movements = [])
    {
        $this->movements = new ArrayCollection($movements);
    }

    /**
     * @return array
     */
    public function movements(): array
    {
        return $this->movements->toArray();
    }

    /**
     * @param Movement $movement
     *
     * @return Ledger
     */
    public function addMovement(Movement $movement): Ledger
    {
        $movements = $this->movements();
        $movements[] = $movement;

        return new self($movements);
    }
}
