<?php

namespace WalletAccountant\Document\Common;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Domain\Common\CurrencyCode;
use WalletAccountant\Domain\Common\Money as MoneyDomain;

/**
 * Money
 *
 * @MongoDB\EmbeddedDocument
 */
class Money
{
    /**
     * @var int
     *
     * @MongoDB\Field(type="int")
     */
    private $amount;

    /**
     * @var CurrencyCode
     *
     * @MongoDB\Field(type="currencycode")
     */
    private $currency;

    /**
     * @param int          $amount
     * @param CurrencyCode $currency
     */
    public function __construct(int $amount, CurrencyCode $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @param MoneyDomain $domain
     *
     * @return Money
     */
    public static function createFromDomain(MoneyDomain $domain): self
    {
        return new self($domain->getAmount(), $domain->getCurrency());
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return CurrencyCode
     */
    public function getCurrency(): CurrencyCode
    {
        return $this->currency;
    }
}
