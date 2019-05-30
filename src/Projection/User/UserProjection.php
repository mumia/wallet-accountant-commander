<?php

namespace WalletAccountant\Projection\User;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\User;
use WalletAccountant\Document\User\Name;
use WalletAccountant\Document\User\Status;
use WalletAccountant\Domain\User\Event\UserNameChanged;
use WalletAccountant\Domain\User\Event\UserPasswordRecovered;
use WalletAccountant\Domain\User\Event\UserPasswordRecoveryInitiated;
use WalletAccountant\Domain\User\Event\UserWasCreated;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;

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
            ->fromCategory(UserProjectionRepositoryInterface::COLLECTION_NAME)
            ->when(
                [
                    UserWasCreated::class => $this->userWasCreatedHandler($projector),
                    UserPasswordRecoveryInitiated::class => $this->userPasswordRecoveryInitiatedHandler($projector),
                    UserPasswordRecovered::class => $this->userPasswordRecoveredHandler($projector),
                    UserNameChanged::class => $this->userUserNameChangedHandler($projector)
                ]
            );

        return $projector;
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     */
    private function userWasCreatedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, UserWasCreated $event) use ($projector): void {
            $name = new Name($event->firstName(), $event->lastName());

            $user = new User(
                $event->id(),
                $event->email(),
                $name,
                $event->roles(),
                $event->password(),
                $event->salt(),
                Status::createFromDomain($event->status())
            );

            $readModel = $projector->readModel();
            $readModel->stack('insert', $user);
        };
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     */
    private function userPasswordRecoveryInitiatedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, UserPasswordRecoveryInitiated $event) use ($projector): void {
            $readModel = $projector->readModel();
            $readModel->stack('passwordRecovery', $event->id(), $event->code(), $event->expiresOn());
        };
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     */
    private function userPasswordRecoveredHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, UserPasswordRecovered $event) use ($projector): void {
            $readModel = $projector->readModel();
            $readModel->stack('passwordRecovered', $event->id(), $event->password());
        };
    }

    /**
     * @param ReadModelProjector $projector
     *
     * @return callable
     *
     * @throws InvalidArgumentException
     */
    private function userUserNameChangedHandler(ReadModelProjector $projector): callable
    {
        return function (array $state, UserNameChanged $event) use ($projector): void {
            $readModel = $projector->readModel();
            $readModel->stack('nameChanged', $event->id(), $event->name());
        };
    }
}
