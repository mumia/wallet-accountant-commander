<?php

namespace WalletAccountant\Tests\Functional\Fixtures\Bank;

use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Domain\Bank\Event\BankWasCreated;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Tests\Functional\Fixtures\AbstractFixture;
use WalletAccountant\Domain\Bank\Bank as BankDomain;

/**
 * Bank
 */
class Bank extends AbstractFixture
{
    public const EVENT_CATEGORY = 'bank';
    public const EVENT_AGGREGATE_ID = 'f9145a03-69f7-4852-88d6-dedd330f839a';

    public const NAME = 'BIC';

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

        $bankWasCreatedEvent = new BankWasCreated($this->getAggregateId(), self::NAME);
        $this->enrichEventWithTestData(
            $bankWasCreatedEvent,
            'eb0af1d6-ccc6-44ad-8490-5ea8eaa3b5d2',
            1,
            BankDomain::class,
            DateTime::now()->subHours(10),
            UserId::createFromString('6eeaf6b5-ce76-4d15-b370-5e148b93c8db')
        );
        $events[] = $bankWasCreatedEvent;

        return $events;
    }
}
