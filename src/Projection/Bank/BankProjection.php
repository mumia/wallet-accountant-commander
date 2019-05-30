<?php

namespace WalletAccountant\Projection\Bank;

use Prooph\EventStore\Projection\ReadModelProjector;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Bank;
use WalletAccountant\Domain\Bank\Event\BankWasCreated;
use WalletAccountant\Domain\Bank\Event\BankWasUpdated;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Projection\AbstractReadModelProjection;

/**
 * BankProjection
 */
final class BankProjection extends AbstractReadModelProjection
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
     *
     * @throws InvalidArgumentException
     */
    private function bankWasCreatedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, BankWasCreated $event) use ($projector): void {
            $authored = AbstractReadModelProjection::createAuthored($event);

            $bank = new Bank(
                BankId::createFromString($event->aggregateId()),
                $event->name()->value(),
                $authored,
                $authored
            );

            $readModel = $projector->readModel();
            $readModel->stack('insert', $bank);
        };
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     *
     * @throws InvalidArgumentException
     */
    private function bankWasUpdatedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, BankWasUpdated $event) use ($projector): void {
            $readModel = $projector->readModel();
            $readModel->stack(
                'update',
                $event->id(),
                $event->name(),
                AbstractReadModelProjection::createAuthored($event)
            );
        };
    }
}
