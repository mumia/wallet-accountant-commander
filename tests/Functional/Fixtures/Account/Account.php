<?php

namespace WalletAccountant\Tests\Functional\Fixtures\Account;

use function var_dump;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Domain\Account\Event\AccountWasCreated;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Tests\Functional\Fixtures\AbstractFixture;
use WalletAccountant\Domain\Account\Account as AccountDomain;

/**
 * Account
 */
class Account extends AbstractFixture
{
    public const EVENT_CATEGORY = 'account';
    public const EVENT_AGGREGATE_ID = '954d6726-abd9-49e5-a9c2-47de3ce890f6';

    public const BANK_ID = 'f9145a03-69f7-4852-88d6-dedd330f839a';
    public const OWNER_ID = '6eeaf6b5-ce76-4d15-b370-5e148b93c8db';
    public const IBAN = 'CH9300762011623852957';

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

        $accountWasCreatedEvent = new AccountWasCreated(
            AccountId::createFromString($this->getAggregateId()),
            BankId::createFromString(self::BANK_ID),
            UserId::createFromString(self::OWNER_ID),
            Iban::createFromString(self::IBAN)
        );
        $this->enrichEventWithTestData(
            $accountWasCreatedEvent,
            'c5dde45e-7ea6-44cd-ba6f-52799f21ec39',
            1,
            AccountDomain::class,
            DateTime::now()->subHours(1),
            UserId::createFromString('6eeaf6b5-ce76-4d15-b370-5e148b93c8db')
        );
        $events[] = $accountWasCreatedEvent;

        return $events;
    }
}
