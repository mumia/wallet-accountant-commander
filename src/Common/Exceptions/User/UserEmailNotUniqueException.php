<?php

namespace WalletAccountant\Common\Exceptions\User;

use LogicException;
use function sprintf;

/**
 * UserEmailNotUniqueException
 */
class UserEmailNotUniqueException extends LogicException
{
    /**
     * @param string $email
     */
    public function __construct(string $email)
    {
        parent::__construct(sprintf('User with email "%s" already exists', $email));
    }
}
