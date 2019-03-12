<?php

namespace WalletAccountant\Common\Exceptions;

use InvalidArgumentException as BaseInvalidArgumentException;

/**
 * InvalidArgumentException
 */
class InvalidArgumentException extends BaseInvalidArgumentException
{
    /**
     * @param BaseInvalidArgumentException $exception
     *
     * @return InvalidArgumentException
     */
    public static function createFromStandardException(BaseInvalidArgumentException $exception): self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
    }
}
