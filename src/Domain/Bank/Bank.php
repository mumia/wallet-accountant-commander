<?php

namespace WalletAccountant\Domain\Bank;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Event\BankWasCreated;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Bank\Name\Name;

class Bank extends AggregateRoot
{
    /**
     * @var BankId
     */
    protected $id;

    /**
     * @var Name
     */
    protected $name;

    /**
     * @return BankId
     */
    public function id() : BankId {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function name() : Name {
        return $this->name;
    }

    /**
     * @param BankId $id
     * @param Name $name
     *
     * @return Bank
     */
    public static function createBank(BankId $id, Name $name): self
    {
        $bank = new self();

        $bank->recordThat(new BankWasCreated($id->toString(), $name->value()));

        return $bank;
    }

    /**
     * @return string
     */
    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    /**
     * @param BankWasCreated $event
     */
    protected function whenBankWasCreated(BankWasCreated $event) : void
    {
        $this->id = BankId::createFromString($event->id());
        $this->name = new Name($event->name());
    }

    /**
     * @param AggregateChanged $event
     */
    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof BankWasCreated) {
            $this->whenBankWasCreated($event);

            return;
        }

        throw new InvalidArgumentException(
            sprintf('event "%s" not supported', get_class($event))
        );
    }
}
