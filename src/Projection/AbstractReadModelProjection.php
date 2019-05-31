<?php

namespace WalletAccountant\Projection;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Common\Authored;
use WalletAccountant\Domain\Common\AbstractAggregateChanged;

/**
 * AbstractReadModelProjection
 */
abstract class AbstractReadModelProjection implements ReadModelProjection
{
    /**
     * @param AbstractAggregateChanged $event
     *
     * @return Authored
     *
     * @throws InvalidArgumentException
     */
    public static function createAuthored(AbstractAggregateChanged $event): Authored
    {
        return new Authored(
            $event->getCreatedBy(),
            DateTime::createFromDateImmutable($event->createdAt())
        );
    }
}
