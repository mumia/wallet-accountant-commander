<?php

namespace WalletAccountant\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Domain\User\Recovery\Recovery as RecoveryDomain;

/**
 * Recovery
 *
 * @MongoDB\EmbeddedDocument
 */
class Recovery
{
    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    private $code;

    /**
     * @var DateTime
     *
     * @MongoDB\Field(type="datetime", name="expires_on")
     */
    private $expiresOn;

    /**
     * @param string   $code
     * @param DateTime $expiresOn
     */
    public function __construct(string $code, DateTime $expiresOn)
    {
        $this->code = $code;
        $this->expiresOn = $expiresOn;
    }

    /**
     * @param RecoveryDomain $recoveryDomain
     *
     * @return Recovery
     */
    public static function createFromDomain(RecoveryDomain $recoveryDomain): self
    {
        $recovery = new self(
            $recoveryDomain->code(),
            $recoveryDomain->expiresOn()
        );

        return $recovery;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return DateTime
     */
    public function getExpiresOn(): DateTime
    {
        return $this->expiresOn;
    }
}
