<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Account\Ledger;
use WalletAccountant\Document\Account\Movement;
use WalletAccountant\Document\Common\Money;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\CurrencyCode;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * Account
 *
 * @MongoDB\Document
 */
final class Account
{
    /**
     * @var AccountId
     *
     * @MongoDB\Id(strategy="none", type="accountid")
     */
    private $id;

    /**
     * @var BankId
     *
     * @MongoDB\Field(type="bankid", name="bank_id")
     */
    private $bankId;

    /**
     * @var UserId
     *
     * @MongoDB\Field(type="userid", name="owner_id")
     */
    private $ownerId;

    /**
     * @var Iban
     *
     * @MongoDB\Field(type="iban")
     */
    private $iban;

    /**
     * @var Money
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\Common\Money", name="current_balance")
     */
    private $currentBalance;

    /**
     * @var Ledger
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\Account\Ledger")
     */
    private $ledger;

    /**
     * @param AccountId $id
     * @param BankId    $bankId
     * @param UserId    $ownerId
     * @param Iban      $iban
     * @param Ledger    $ledger
     *
     * @throws InvalidArgumentException
     */
    public function __construct(AccountId $id, BankId $bankId, UserId $ownerId, Iban $iban, Ledger $ledger)
    {
        $this->id = $id;
        $this->bankId = $bankId;
        $this->ownerId = $ownerId;
        $this->iban = $iban;
        $this->ledger = $ledger;

        $amount = 0;
        /** @var Movement $movement */
        foreach ($this->getLedger()->movements() as $movement) {
            if ($movement->getType()->isDebit()) {
                $amount -= $movement->getValue()->getAmount();
            } else {
                $amount += $movement->getValue()->getAmount();
            }
        }

        $this->currentBalance = new Money($amount, CurrencyCode::createEuro());
    }

    /**
     * @return AccountId
     *
     * @throws InvalidArgumentException
     */
    public function getId(): AccountId
    {
        return $this->id;
    }

    /**
     * @return BankId
     *
     * @throws InvalidArgumentException
     */
    public function getBankId(): BankId
    {
        return $this->bankId;
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function getOwnerId(): UserId
    {
        return $this->ownerId;
    }

    /**
     * @return Iban
     *
     * @throws InvalidArgumentException
     */
    public function getIban(): Iban
    {
        return $this->iban;
    }

    /**
     * @return Money
     */
    public function getCurrentBalance(): Money
    {
        return $this->currentBalance;
    }

    /**
     * @return Ledger
     */
    public function getLedger(): Ledger
    {
        return $this->ledger;
    }
}
