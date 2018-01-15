<?php

namespace WalletAccountant\Domain\User\Status;

/**
 * Status
 */
class Status
{
    /**
     * @var bool
     */
    private $accountExpired;

    /**
     * @var bool
     */
    private $accountLocked;

    /**
     * @var bool
     */
    private $credentialsExpired;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @param bool $accountExpired
     * @param bool $accountLocked
     * @param bool $credentialsExpired
     * @param bool $enabled
     */
    public function __construct(bool $accountExpired, bool $accountLocked, bool $credentialsExpired, bool $enabled)
    {
        $this->accountExpired = $accountExpired;
        $this->accountLocked = $accountLocked;
        $this->credentialsExpired = $credentialsExpired;
        $this->enabled = $enabled;
    }

    /**
     * @return Status
     */
    public static function createDefault(): self
    {
        return new self(false, false, true, true);
    }

    /**
     * @return bool
     */
    public function isAccountExpired(): bool
    {
        return $this->accountExpired;
    }

    /**
     * @return bool
     */
    public function isAccountLocked(): bool
    {
        return $this->accountLocked;
    }

    /**
     * @return bool
     */
    public function isCredentialsExpired(): bool
    {
        return $this->credentialsExpired;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
