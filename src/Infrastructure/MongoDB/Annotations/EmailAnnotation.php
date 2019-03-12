<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * EmailAnnotation
 *
 * @Annotation
 */
class EmailAnnotation extends AbstractField
{
    const TYPE = 'email';

    public $type = self::TYPE;
}
