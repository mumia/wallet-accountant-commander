<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use Respect\Validation\Validator;
use WalletAccountant\Common\Stringable\StringableInterface;

/**
 * AbstractStringableType
 */
abstract class AbstractStringableType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value): string
    {
        /** @var StringableInterface $value */
        if ($value === null) {
            return $value;
        }

        // Not really sure why this value would ever be a string, but it is...
        if (Validator::stringType()->validate($value)) {
            return $value;
        }

        return $value->toString();
    }

    /**
     * @return string
     */
    public function closureToMongo(): string
    {
        return 'if ($value === null) { $return = $value; } else { $return $value->toString(); }';
    }

    /**
     * @return string
     */
    public function closureToPHP(): string
    {
        return sprintf(
            'if ($value === null) { $return = null; } else { $return = \%s::createFromString($value); }',
            $this->getClass()
        );
    }

    /**
     * @return string
     */
    abstract protected function getClass(): string;
}
