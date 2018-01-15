<?php

namespace WalletAccountant\Domain\User\Command;

use WalletAccountant\Domain\Common\Command;

/**
 * RecoverUserPassword
 */
final class RecoverUserPassword extends Command
{
    private const CODE = 'code';
    private const PASSWORD = 'password';
    private const REPEAT_PASSWORD = 'repeat_password';

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->payload()[self::CODE];
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->payload()[self::PASSWORD];
    }

    /**
     * @return string
     */
    public function repeatPassword(): string
    {
        return $this->payload()[self::REPEAT_PASSWORD];
    }
}
