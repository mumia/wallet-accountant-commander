<?php

namespace WalletAccountant\Common\Exceptions;

use function json_encode;
use LogicException;
use Throwable;

/**
 * CommandDispatchException
 */
class CommandDispatchException extends LogicException
{
    /**
     * @param string    $class
     * @param array     $payload
     * @param Throwable $previous
     *
     * @return CommandDispatchException
     */
    public static function withClassAndPayload(
        string $class,
        array $payload,
        Throwable $previous
    ): self {
        $jsonPayload = json_encode($payload);

        return new self(
            sprintf(
                'failed to dispatch command "%s" with payload "%s": "%s"',
                $class,
                $jsonPayload,
                $previous->getMessage()
            ),
            0,
            $previous
        );
    }
}
