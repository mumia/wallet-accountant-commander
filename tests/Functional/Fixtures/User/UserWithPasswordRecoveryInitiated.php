<?php

namespace WalletAccountant\Tests\Functional\Fixtures\User;

use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Domain\User\Event\UserPasswordRecovered;
use WalletAccountant\Domain\User\Event\UserPasswordRecoveryInitiated;
use WalletAccountant\Domain\User\Event\UserWasCreated;
use WalletAccountant\Domain\User\Status\Status;
use WalletAccountant\Tests\Functional\Fixtures\AbstractFixture;
use WalletAccountant\Domain\User\User as UserDomain;

/**
 * UserWithPasswordRecoveryInitiated
 */
class UserWithPasswordRecoveryInitiated extends AbstractFixture
{
    public const EVENT_CATEGORY = 'user';
    public const EVENT_AGGREGATE_ID = '0960f730-824b-4253-ba32-43e508833733';

    public const EMAIL = 'withrecoveryinitiated@faketestdomain.tld';
    public const PASSWORD_RECOVERY_CODE = '43b0e9b57e096d89';

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
            $this->getAggregateId(),
            self::EMAIL,
            'first_name',
            'last_name',
            '',
            'rwIFHkvWtG89swC3K1YS29PFm7bJdS0tyENY2X9w6wGGsGE7BupqKsMcfgChwqf476ItL/j0njinkXtaJkbz5w==',
            [],
            new Status(false, false, true, true)
        );
        $this->enrichEventWithTestData(
            $userWasCreatedEvent,
            'eb0af1d6-ccc6-44ad-8490-5ea8eaa3b5d2',
            1,
            UserDomain::class,
            $now->subDay(1),
            null
        );
        $events[] = $userWasCreatedEvent;

        $userPasswordRecoveryInitiated = new UserPasswordRecoveryInitiated(
            $this->getAggregateId(),
            self::EMAIL,
            self::PASSWORD_RECOVERY_CODE,
            $now->subDay(1)->addHours(360)
        );
        $this->enrichEventWithTestData(
            $userPasswordRecoveryInitiated,
            '71f8172f-6a43-404e-901f-c8c81d922f5e',
            2,
            UserDomain::class,
            $now->subDay(1),
            null
        );
        $events[] = $userPasswordRecoveryInitiated;

        return $events;
    }
}
