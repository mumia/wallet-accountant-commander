<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * DateTimeAnnotation
 *
 * @Annotation
 */
class DateTimeAnnotation extends AbstractField
{
    public $type = 'datetime';
}