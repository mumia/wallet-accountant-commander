<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;
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
     * @param AccountId $id
     * @param BankId    $bankId
     * @param UserId    $ownerId
     * @param Iban      $iban
     */
    public function __construct(AccountId $id, BankId $bankId, UserId $ownerId, Iban $iban)
    {
        $this->id = $id;
        $this->bankId = $bankId;
        $this->ownerId = $ownerId;
        $this->iban = $iban;
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

}
