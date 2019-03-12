<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * AccountIdAnnotation
 *
 * @Annotation
 */
class MovementTypeAnnotation extends AbstractField
{
    const TYPE = 'movementtype';

    public $type = self::TYPE;
}
