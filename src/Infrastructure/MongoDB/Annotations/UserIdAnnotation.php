<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * UserIdAnnotation
 *
 * @Annotation
 */
class UserIdAnnotation extends AbstractField
{
    public $type = 'userid';
}
