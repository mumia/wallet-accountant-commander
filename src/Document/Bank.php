<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Document\Common\Authored;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * Bank
 *
 * @MongoDB\Document
 */
final class Bank
{
    /**
     * @var string
     *
     * @MongoDB\Id(strategy="none")
     */
    private $aggregateId;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    private $name;

    /**
     * @var Authored
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\Common\Authored")
     */
    private $created;

    /**
     * @var Authored
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\Common\Authored")
     */
    private $updated;

    /**
     * @param string   $aggregateId
     * @param string   $name
     * @param Authored $created
     * @param Authored $updated
     */
    public function __construct(string $aggregateId, string $name, Authored $created, Authored $updated)
    {
        $this->aggregateId = $aggregateId;
        $this->name = $name;
        $this->created = $created;
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Authored
     */
    public function getCreated(): Authored
    {
        return $this->created;
    }

    /**
     * @return Authored
     */
    public function getUpdated(): Authored
    {
        return $this->updated;
    }
}
