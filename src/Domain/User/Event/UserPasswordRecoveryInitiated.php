<?php

namespace WalletAccountant\Domain\User\Event;

use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Common\AbstractAggregateChanged;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * UserPasswordRecoveryInitiated
 */
final class UserPasswordRecoveryInitiated extends AbstractAggregateChanged
{
    private const EMAIL = 'email';
    private const CODE = 'code';
    private const EXPIRES_ON = 'expires_on';

    /**
     * UserPasswordRecoveryInitiated constructor.
     * @param UserId $id
     * @param Email $email
     * @param string $code
     * @param DateTime $expiresOn
     */
    public function __construct(
        UserId $id,
        Email $email,
        string $code,
        DateTime $expiresOn
    ) {
        parent::__construct(
            $id->toString(),
            [
                self::EMAIL => $email,
                self::CODE => $code,
                self::EXPIRES_ON => $expiresOn->toDateTimeMicroFull()
            ]
        );
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function id(): UserId
    {
        return UserId::createFromString($this->aggregateId());
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->payload()[self::EMAIL];
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
        return DateTime::createFromDateTimeMicroFullFormat($this->payload()[self::EXPIRES_ON]);
    }
}
