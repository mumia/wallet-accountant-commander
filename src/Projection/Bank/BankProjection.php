<?php

namespace WalletAccountant\Projection\Bank;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;
use WalletAccountant\Document\Bank;
use WalletAccountant\Document\User;
use WalletAccountant\Document\User\Name;
use WalletAccountant\Document\User\Status;
use WalletAccountant\Domain\Bank\Event\BankWasCreated;
use WalletAccountant\Domain\Bank\Event\BankWasUpdated;
use WalletAccountant\Domain\User\Event\UserPasswordRecovered;
use WalletAccountant\Domain\User\Event\UserPasswordRecoveryInitiated;
use WalletAccountant\Domain\User\Event\UserWasCreated;

/**
 * BankProjection
 */
final class BankProjection implements ReadModelProjection
{
    /**
     * @param ReadModelProjector $projector
     *
     * @return ReadModelProjector
     */
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector
            ->fromCategory('bank')
            ->when(
                [
                    BankWasCreated::class => $this->bankWasCreatedHandler($projector),
                    BankWasUpdated::class => $this->bankWasUpdatedHandler($projector)
                ]
            );

        return $projector;
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     */
    private function bankWasCreatedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, BankWasCreated $event) use ($projector): void {
            $bank = new Bank($event->aggregateId(), $event->name());

            $readModel = $projector->readModel();
            $readModel->stack('insert', $bank);
        };
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     */
    private function bankWasUpdatedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, BankWasUpdated $event) use ($projector): void {
            $readModel = $projector->readModel();
            $readModel->stack('update', $event->id(), $event->name());
        };
    }
}
