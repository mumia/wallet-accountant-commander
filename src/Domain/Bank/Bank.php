<?php

namespace WalletAccountant\Domain\Bank;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Event\BankWasUpdated;
use WalletAccountant\Domain\Bank\Event\BankWasCreated;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * Bank
 */
final class Bank extends AggregateRoot
{
    /**
     * @var BankId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param BankId $id
     * @param string $name
     *
     * @return Bank
     */
    public static function createBank(BankId $id, string $name): self
    {
        $bank = new self();

        $bank->recordThat(new BankWasCreated($id, $name));

        return $bank;
    }

    /**
     * @return BankId
     */
    public function id(): BankId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->recordThat(new BankWasUpdated($this->aggregateId(), $name));
    }

    /**
     * @return string
     */
    protected function aggregateId(): string
    {
        return $this->id()->toString();
    }

    /**
     * @param BankWasCreated $event
     *
     * @throws InvalidArgumentException
     */
    protected function whenBankWasCreated(BankWasCreated $event): void
    {
        $this->id = BankId::createFromString($event->id());
        $this->name = $event->name();
    }

    /**
     * @param BankWasUpdated $event
     *
     * @throws InvalidArgumentException
     */
    protected function whenBankWasUpdated(BankWasUpdated $event): void
    {
        $this->name = $event->name();
    }

    /**
     * @param AggregateChanged $event
     *
     * @throws InvalidArgumentException
     */
    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof BankWasCreated) {
            $this->whenBankWasCreated($event);

            return;
        }

        if ($event instanceof BankWasUpdated) {
            $this->whenBankWasUpdated($event);

            return;
        }

        throw new InvalidArgumentException(
            sprintf('event "%s" not supported', get_class($event))
        );
    }
}
