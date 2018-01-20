<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
     * @param string $aggregateId
     * @param string $name
     */
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
