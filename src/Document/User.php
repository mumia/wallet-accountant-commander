<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use WalletAccountant\Document\User\Name;

/**
 * User
 *
 * @MongoDB\Document
 */
class User
{
    /**
     * @MongoDB\Id(strategy="none")
     */
    public $email;

    /**
     * @var Name
     *
     * @MongoDB\EmbedOne(targetDocument="User\Name")
     */
    public $name;
}
