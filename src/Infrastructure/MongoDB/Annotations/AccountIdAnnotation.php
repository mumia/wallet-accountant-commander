<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * AccountIdAnnotation
 *
 * @Annotation
 */
class AccountIdAnnotation extends AbstractField
{
    const TYPE = 'accountid';

    public $type = self::TYPE;
}
