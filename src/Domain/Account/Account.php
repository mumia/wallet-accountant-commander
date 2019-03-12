<?php

namespace WalletAccountant\Domain\Account;

use function get_class;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Event\AccountOwnerWasUpdated;
use WalletAccountant\Domain\Account\Event\AccountWasCreated;
use WalletAccountant\Domain\Account\Event\MovementAddedToLedger;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Account\Ledger\Ledger;
use WalletAccountant\Domain\Account\Ledger\Movement;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\CurrencyCode;
use WalletAccountant\Domain\Common\Money;
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
     * @var Money
     */
    private $currentBalance;

    /**
     * @var Ledger
     */
    private $ledger;

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
     * @param Movement $movement
     */
    public function addMovementToLedger(Movement $movement): void
    {
        $this->recordThat(new MovementAddedToLedger($this->id, $movement));
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
        $this->currentBalance = new Money(0, CurrencyCode::createEuro());
        $this->ledger = new Ledger();
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
     * @param MovementAddedToLedger $event
     *
     * @throws InvalidArgumentException
     */
    protected function whenMovementAddedToLedger(MovementAddedToLedger $event): void
    {
        $movement = $event->movement();

        $this->currentBalance = $movement->calculateNewBalance($this->currentBalance);
        $this->ledger->append($movement);
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

        if ($event instanceof MovementAddedToLedger) {
            $this->whenMovementAddedToLedger($event);

            return;
        }

        throw new InvalidArgumentException(
            sprintf('event "%s" not supported', get_class($event))
        );
    }
}
