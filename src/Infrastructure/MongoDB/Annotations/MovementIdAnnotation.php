<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * AccountIdAnnotation
 *
 * @Annotation
 */
class MovementIdAnnotation extends AbstractField
{
    const TYPE = 'movementid';

    public $type = self::TYPE;
}
