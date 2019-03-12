<?php

namespace WalletAccountant\Infrastructure\MongoDB;

/**
 * DroppableRepositoryInterface
 */
interface DroppableRepositoryInterface
{
    public function dropCollection(): void;
}
