<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Bank
 * @package WalletAccountant\Document
 *
 * @MongoDB\Document
 */
class Bank
{
    /**
     * @var string
     *
     * @MongoDB\Id(strategy="none", name="aggregate_id")
     */
    private $aggregateId;

    /**
     * @var string
     *
     * @MongoDB\EmbedOne(type="string")
     */
    private $name;

    public function __construct(string $aggregateId, string $name)
    {
        $this->aggregateId = $aggregateId;
        $this->name = $name;
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
}
