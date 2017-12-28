<?php

namespace WalletAccountant\Document\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Name
 *
 * @MongoDB\EmbeddedDocument
 */
class Name
{
    /**
     * @MongoDB\Field(type="string")
     */
    public $first;

    /**
     * @MongoDB\Field(type="string")
     */
    public $last;
}
