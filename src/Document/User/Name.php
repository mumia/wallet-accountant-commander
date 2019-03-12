<?php

namespace WalletAccountant\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Domain\User\Name\Name as NameDomain;

/**
 * Name
 *
 * @MongoDB\EmbeddedDocument
 */
class Name
{
    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    private $first;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    private $last;

    /**
     * @param string $first
     * @param string $last
     */
    public function __construct(string $first, string $last)
    {
        $this->first = $first;
        $this->last = $last;
    }

    /**
     * @param NameDomain $nameDomain
     *
     * @return Name
     */
    public static function createFromDomain(NameDomain $nameDomain): self
    {
        return new self($nameDomain->first(), $nameDomain->last());
    }

    /**
     * @return string
     */
    public function getFirst(): string
    {
        return $this->first;
    }

    /**
     * @return string
     */
    public function getLast(): string
    {
        return $this->last;
    }
}
