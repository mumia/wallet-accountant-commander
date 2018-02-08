<?php

namespace WalletAccountant\Document\Common;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * Authored
 *
 * @MongoDB\EmbeddedDocument
 */
class Authored
{
    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
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
        $this->by = $by->toString();
        $this->on = $on;
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function getBy(): UserId
    {
        return UserId::createFromString($this->by);
    }

    /**
     * @return DateTime
     */
    public function getOn(): DateTime
    {
        return $this->on;
    }
}
