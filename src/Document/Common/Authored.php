<?php

namespace WalletAccountant\Document\Common;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Document\User\UserId;

/**
 * Authored
 *
 * @MongoDB\EmbeddedDocument
 */
class Authored
{
    /**
     * @var UserId
     *
     * @MongoDB\Field(type="userid")
     */
    private $by;

    /**
     * @var DateTime
     *
     * @MongoDB\Field(type="datetime")
     */
    private $on;

    /**
     * @param UserId   $by
     * @param DateTime $on
     */
    public function __construct(UserId $by, DateTime $on)
    {
        $this->by = $by;
        $this->on = $on;
    }

    /**
     * @return UserId
     */
    public function getBy(): UserId
    {
        return $this->by;
    }

    /**
     * @return DateTime
     */
    public function getOn(): DateTime
    {
        return $this->on;
    }
}
