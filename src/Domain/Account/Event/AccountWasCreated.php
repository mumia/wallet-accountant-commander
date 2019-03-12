<?php

namespace WalletAccountant\Domain\Account\Event;

use Prooph\EventSourcing\AggregateChanged;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * AccountWasCreated
 */
class AccountWasCreated extends AggregateChanged
{
    private const BANK_ID = 'bank_id';
    private const OWNER_ID = 'owner_id';
    private const IBAN = 'iban';

    /**
     * @param AccountId $id
     * @param BankId    $bankId
     * @param UserId    $ownerId
     * @param Iban      $iban
     */
    public function __construct(AccountId $id, BankId $bankId, UserId $ownerId, Iban $iban)
    {
        parent::__construct(
            $id,
            [
                self::BANK_ID => $bankId->toString(),
                self::OWNER_ID => $ownerId->toString(),
                self::IBAN => $iban->toString()
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
     * @return BankId
     *
     * @throws InvalidArgumentException
     */
    public function bankId(): BankId
    {
        return BankId::createFromString($this->payload()[self::BANK_ID]);
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function ownerId(): UserId
    {
        return UserId::createFromString($this->payload()[self::OWNER_ID]);
    }

    /**
     * @return Iban
     *
     * @throws InvalidArgumentException
     */
    public function iban(): Iban
    {
        return Iban::createFromString($this->payload()[self::IBAN]);
    }
}
