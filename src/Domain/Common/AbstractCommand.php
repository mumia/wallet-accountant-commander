<?php

namespace WalletAccountant\Domain\Common;

use Prooph\Common\Messaging\Command as ProophCommand;

/**
 * Command
 */
abstract class AbstractCommand extends ProophCommand
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->init();
        $this->payload = $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function payload(): array
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }
}
