<?php

namespace WalletAccountant\Domain\Account;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Event\AccountOwnerWasUpdated;
use WalletAccountant\Domain\Account\Event\AccountWasCreated;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * Account
 */
class Account extends AggregateRoot
{
    /**
     * @var AccountId
     */
    private $id;

    /**
     * @var BankId
     */
    private $bankId;

    /**
     * @var UserId
     */
    private $ownerId;

    /**
     * @var Iban
     */
    private $iban;

    /**
     * @param AccountId $id
     * @param BankId    $bankId
     * @param UserId    $ownerId
     * @param Iban      $iban
     *
     * @return Account
     */
    public static function createAccount(AccountId $id, BankId $bankId, UserId $ownerId, Iban $iban): self
    {
        $account = new self();

        $account->recordThat(new AccountWasCreated($id, $bankId, $ownerId, $iban));

        return $account;
    }

    /**
     * @param UserId $ownerId
     */
    public function setOwnerId(UserId $ownerId): void
    {
        $this->recordThat(new AccountOwnerWasUpdated($this->id, $ownerId));
    }

    /**
     * @return string
     */
    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    /**
     * @param AccountWasCreated $event
     *
     * @throws InvalidArgumentException
     */
    protected function whenAccountWasCreated(AccountWasCreated $event): void
    {
        $this->id = $event->id();
        $this->bankId = $event->bankId();
        $this->ownerId = $event->ownerId();
        $this->iban = $event->iban();
    }

    /**
     * @param AccountOwnerWasUpdated $event
     *
     * @throws InvalidArgumentException
     */
    protected function whenAccountOwnerWasUpdated(AccountOwnerWasUpdated $event): void
    {
        $this->ownerId = $event->ownerId();
    }

    /**
     * @param AggregateChanged $event
     *
     * @throws InvalidArgumentException
     */
    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof AccountWasCreated) {
            $this->whenAccountWasCreated($event);

            return;
        }

        if ($event instanceof AccountOwnerWasUpdated) {
            $this->whenAccountOwnerWasUpdated($event);

            return;
        }

        throw new InvalidArgumentException(
            sprintf('event "%s" not supported', get_class($event))
        );
    }
}
