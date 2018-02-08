<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * BankIdAnnotation
 *
 * @Annotation
 */
class BankIdAnnotation extends AbstractField
{
    const TYPE = 'bankid';

    public $type = self::TYPE;
}
