<?php

namespace WalletAccountant\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use function sprintf;
use WalletAccountant\Domain\User\Status\Status as StatusDomain;

/**
 * Status
 *
 * @MongoDB\EmbeddedDocument
 */
class Status
{
    /**
     * @var bool
     *
     * @MongoDB\Field(type="boolean", name="account_expired")
     */
    private $accountExpired;

    /**
     * @var bool
     *
     * @MongoDB\Field(type="boolean", name="account_locked")
     */
    private $accountLocked;

    /**
     * @var bool
     *
     * @MongoDB\Field(type="boolean", name="credentials_expired")
     */
    private $credentialsExpired;

    /**
     * @var bool
     *
     * @MongoDB\Field(type="boolean", name="enabled")
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
     * @param StatusDomain $status
     *
     * @return Status
     */
    public static function createFromDomain(StatusDomain $status): self
    {
        return new self(
            $status->isAccountExpired(),
            $status->isAccountLocked(),
            $status->isCredentialsExpired(),
            $status->isEnabled()
        );
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

    /**
     * @return bool
     */
    public function canLogin(): bool
    {
        return !$this->isAccountExpired()
            && !$this->isAccountLocked()
            && !$this->isCredentialsExpired()
            && $this->isEnabled();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return sprintf(
            'acct_expired: %d, acct_locker: %d, credentials_expired: %d, enabled: %d',
            (int)$this->isAccountExpired(),
            (int)$this->isAccountLocked(),
            (int)$this->isCredentialsExpired(),
            (int)$this->isEnabled()
        );
    }
}
