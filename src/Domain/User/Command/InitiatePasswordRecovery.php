<?php

namespace WalletAccountant\Domain\User\Command;

use Respect\Validation\Validator;
use function sprintf;
use WalletAccountant\Domain\Common\Command;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * InitiatePasswordRecovery
 */
final class InitiatePasswordRecovery extends Command
{
    private const ID = 'id';

    /**
     * @param string $id
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $id)
    {
        if (!Validator::stringType()->notEmpty()->validate($id)) {
            throw new InvalidArgumentException(
                sprintf('unable to initiate password recovery, invalid user id "%s"', $id)
            );
        }

        parent::__construct([self::ID => $id]);
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function userId(): UserId
    {
        return UserId::createFromString($this->payload()[self::ID]);
    }
}
