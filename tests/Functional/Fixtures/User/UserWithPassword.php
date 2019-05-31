<?php

namespace WalletAccountant\Tests\Functional\Fixtures\User;

use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Event\UserPasswordRecovered;
use WalletAccountant\Domain\User\Event\UserPasswordRecoveryInitiated;
use WalletAccountant\Domain\User\Event\UserWasCreated;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;
use WalletAccountant\Domain\User\Status\Status;
use WalletAccountant\Tests\Functional\Fixtures\AbstractFixture;
use WalletAccountant\Domain\User\User as UserDomain;

/**
 * UserWithPassword
 */
class UserWithPassword extends AbstractFixture
{
    public const EVENT_CATEGORY = 'user';
    public const EVENT_AGGREGATE_ID = '6eeaf6b5-ce76-4d15-b370-5e148b93c8db';

    public const EMAIL = 'fakeemail@faketestdomain.tld';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const PASSWORD = 'thepassword';


    /**
     * @return string
     */
    protected function getCategory(): string
    {
        return self::EVENT_CATEGORY;
    }

    /**
     * @return string
     */
    protected function getAggregateId(): string
    {
        return self::EVENT_AGGREGATE_ID;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEvents(): array
    {
        $events = [];
        $now = DateTime::now();

        $userWasCreatedEvent = new UserWasCreated(
            $this->getUserId(),
            Email::createFromString(self::EMAIL),
            new Name(self::FIRST_NAME, self::LAST_NAME),
            '',
            'rwIFHkvWtG89swC3K1YS29PFm7bJdS0tyENY2X9w6wGGsGE7BupqKsMcfgChwqf476ItL/j0njinkXtaJkbz5w==',
            [],
            new Status(false, false, true, true)
        );
        $this->enrichEventWithTestData(
            $userWasCreatedEvent,
            '54cdef96-57c6-45cd-a110-c994e05a2fd9',
            1,
            UserDomain::class,
            $now->subDay(1),
            null
        );
        $events[] = $userWasCreatedEvent;

        $userPasswordRecoveryInitiated = new UserPasswordRecoveryInitiated(
            $this->getUserId(),
            Email::createFromString(self::EMAIL),
            '43b0e9b57e096d88',
            $now->subDay(1)->addHours(360)
        );
        $this->enrichEventWithTestData(
            $userPasswordRecoveryInitiated,
            'aca6284d-fd70-405c-9fac-4fb16c434663',
            2,
            UserDomain::class,
            $now->subDay(1),
            null
        );
        $events[] = $userPasswordRecoveryInitiated;

        $userPasswordRecovered = new UserPasswordRecovered(
            $this->getUserId(),
            'mnMKZA9zr/mRXvF/PMBr21ersndHnRd7AaEeNq+vlxP1XoBfFi2MciKkTLJa62zjSCze9st/WtBhQ/eHGPS8Qg=='
        );
        $this->enrichEventWithTestData(
            $userPasswordRecovered,
            '9d756236-1745-4136-85ce-3209a341de0f',
            3,
            UserDomain::class,
            $now,
            null
        );
        $events[] = $userPasswordRecovered;

        return $events;
    }

    /**
     * @return UserId
     */
    private function getUserId(): UserId
    {
        return UserId::createFromString($this->getAggregateId());
    }
}
