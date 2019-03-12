<?php

namespace WalletAccountant\Projection\Account;

use Prooph\EventStore\Projection\ReadModelProjector;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Account;
use WalletAccountant\Document\Account\Ledger;
use WalletAccountant\Domain\Account\Event\AccountOwnerWasUpdated;
use WalletAccountant\Domain\Account\Event\AccountWasCreated;
use WalletAccountant\Domain\Account\Event\MovementAddedToLedger;
use WalletAccountant\Projection\AbstractReadModelProjection;

/**
 * AccountProjection
 */
final class AccountProjection extends AbstractReadModelProjection
{
    /**
     * @param ReadModelProjector $projector
     *
     * @return ReadModelProjector
     *
     * @throws InvalidArgumentException
     */
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector
            ->fromCategory('account')
            ->when(
                [
                    AccountWasCreated::class => $this->accountWasCreatedHandler($projector),
                    AccountOwnerWasUpdated::class => $this->accountOwnerWasUpdatedHandler($projector),
                    MovementAddedToLedger::class => $this->movementAddedToLedgerHandler($projector)
                ]
            );

        return $projector;
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     *
     * @throws InvalidArgumentException
     */
    private function accountWasCreatedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, AccountWasCreated $event) use ($projector): void {
            $account = new Account($event->id(), $event->bankId(), $event->ownerId(), $event->iban(), New Ledger());

            $readModel = $projector->readModel();
            $readModel->stack('insert', $account);
        };
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     *
     * @throws InvalidArgumentException
     */
    private function accountOwnerWasUpdatedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, AccountOwnerWasUpdated $event) use ($projector): void {
            $readModel = $projector->readModel();
            $readModel->stack(
                'updateOwner',
                $event->id(),
                $event->ownerId()
            );
        };
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     *
     * @throws InvalidArgumentException
     */
    private function movementAddedToLedgerHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, MovementAddedToLedger $event) use ($projector): void {
            $readModel = $projector->readModel();
            $readModel->stack(
                'addMovementToLedger',
                $event->id(),
                $event->movement()
            );
        };
    }
}
