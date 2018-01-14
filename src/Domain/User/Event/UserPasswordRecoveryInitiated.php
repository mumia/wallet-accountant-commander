<?php

namespace WalletAccountant\Domain\User\Event;

use Prooph\EventSourcing\AggregateChanged;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * UserPasswordRecoveryInitiated
 */
final class UserPasswordRecoveryInitiated extends AggregateChanged
{
    private const CODE = 'code';
    private const EXPIRES_ON = 'expires_on';

    /**
     * @param string   $id
     * @param string   $code
     * @param DateTime $expiresOn
     */
    public function __construct(
        string $id,
        string $code,
        DateTime $expiresOn
    ) {
        parent::__construct(
            $id,
            [
                self::CODE => $code,
                self::EXPIRES_ON => $expiresOn->toDateTimeMicro()
            ]
        );
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->aggregateId();
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->payload()[self::CODE];
    }

    /**
     * @return DateTime
     *
     * @throws InvalidArgumentException
     */
    public function expiresOn(): DateTime
    {
        return DateTime::createFromDateTimeMicroFormat($this->payload()[self::EXPIRES_ON]);
    }
}