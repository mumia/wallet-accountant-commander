<?php

namespace WalletAccountant\Domain\User\Command;

use WalletAccountant\Domain\Common\AbstractCommand;

/**
 * RecoverUserPassword
 */
final class RecoverUserPassword extends AbstractCommand
{
    public const CODE = 'code';
    public const PASSWORD = 'password';
    public const REPEAT_PASSWORD = 'repeat_password';

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
