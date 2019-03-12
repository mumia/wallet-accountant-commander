<?php

namespace WalletAccountant\Infrastructure\MongoDB\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * CurrencyCodeAnnotation
 *
 * @Annotation
 */
class CurrencyCodeAnnotation extends AbstractField
{
    const TYPE = 'currencycode';

    public $type = self::TYPE;
}
