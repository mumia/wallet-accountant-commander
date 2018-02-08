<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * IbanAnnotation
 *
 * @Annotation
 */
class IbanAnnotation extends AbstractField
{
    const TYPE = 'iban';

    public $type = self::TYPE;
}
