<?php

namespace WalletAccountant\Projection\User;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;
use WalletAccountant\Document\User;
use WalletAccountant\Document\User\Name;
use WalletAccountant\Domain\User\Event\UserWasCreated;

/**
 * Class UserProjection
 */
final class UserProjection implements ReadModelProjection
{
    /**
     * @param ReadModelProjector $projector
     *
     * @return ReadModelProjector
     */
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector
            ->fromCategory('user')
            ->when(
                [
                    UserWasCreated::class => function (array $state, UserWasCreated $event) use ($projector): void {
                        $user = new User();
                        $name = new Name();
                        $name->first = $event->firstName();
                        $name->last = $event->lastName();

                        $user->email = $event->email();
                        $user->name = $name;

                        $readModel = $projector->readModel();
                        $readModel->stack('insert', $user);
                    }
                ]
            );

        return $projector;
    }
}
