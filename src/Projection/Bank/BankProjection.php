<?php

namespace WalletAccountant\Projection\Bank;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;
use WalletAccountant\Document\Bank;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Domain\Bank\Event\BankWasCreated;

/**
 * Class BankProjection
 * @package WalletAccountant\Projection\Bank
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
            ->fromCategory(BankProjectionRepositoryInterface::COLLECTION_NAME)
            ->when(
                [
                    BankWasCreated::class => $this->bankWasCreatedHandler($projector),
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
            $bank = new Bank(
                $event->aggregateId(),
                $event->name()
            );

            $readModel = $projector->readModel();
            $readModel->stack('insert', $bank);
        };
    }
}
