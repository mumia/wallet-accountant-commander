<?php

namespace WalletAccountant\Domain\User;

use function base64_encode;
use function get_class;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use function random_bytes;
use WalletAccountant\Common\Exceptions\User\LogicException;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Event\UserPasswordRecovered;
use WalletAccountant\Domain\User\Event\UserPasswordRecoveryInitiated;
use WalletAccountant\Domain\User\Event\UserWasCreated;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;
use WalletAccountant\Domain\User\Recovery\Recovery;
use WalletAccountant\Domain\User\Status\Status;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * User
 */
final class User extends AggregateRoot
{
    /**
     * @var UserId
     */
    protected $id;

    /**
     * @var Email
     */
    protected $email;

    /**
     * @var Name
     */
    protected $name;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var Status
     */
    protected $status;

    /**
     * @var Recovery
     */
    private $recovery;

    /**
     * @param UserId $id
     * @param Email  $email
     * @param Name   $name
     *
     * @return User
     */
    public static function createUser(
        UserId $id,
        Email $email,
        Name $name
    ): User {
        $user = new self();

        $user->recordThat(
            new UserWasCreated(
                $id->toString(),
                $email->toString(),
                $name->first(),
                $name->last(),
                '',
                base64_encode(random_bytes(64)),
                [],
                Status::createDefault()
            )
        );

        return $user;
    }

    /**
     * @return UserId
     */
    public function id(): UserId
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function email(): Email
    {
        return $this->email;
    }

    /**
     * @return Name
     */
    public function name(): Name
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function roles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function password(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function salt(): string
    {
        return $this->salt;
    }

    /**
     * @return Status
     */
    public function status(): Status
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function hasRecovery(): bool
    {
        return $this->recovery instanceof Recovery;
    }

    /**
     * @return null|Recovery
     */
    public function recovery(): ?Recovery
    {
        return $this->recovery;
    }

    /**
     * @param string|null $recoveryCode
     *
     * @throws InvalidArgumentException
     */
    public function initiatePasswordRecovery(string $recoveryCode = null): void
    {
        $this->status = new Status(
            $this->status()->isAccountExpired(),
            $this->status()->isAccountLocked(),
            true,
            $this->status()->isEnabled()
        );

        $recovery = Recovery::create($recoveryCode);

        $this->recordThat(
            new UserPasswordRecoveryInitiated(
                $this->id()->toString(),
                $recovery->code(),
                $recovery->expiresOn()
            )
        );
    }

    /**
     * @param string $recoveryCode
     * @param string $encodedPassword
     *
     * @throws LogicException
     */
    public function recoverPassword(string $recoveryCode, string $encodedPassword): void
    {
        if (!$this->hasRecovery()) {
            throw new LogicException('user is not in password recovery mode');
        }

        if (!$this->recovery()->validateRecovery($recoveryCode)) {
            throw new LogicException(sprintf('user password recovery code "%s" does not match', $recoveryCode));
        }

        $this->password = $encodedPassword;
        $this->status = new Status(
            $this->status()->isAccountExpired(),
            $this->status()->isAccountLocked(),
            false,
            $this->status()->isEnabled()
        );
        $this->recovery = null;

        $this->recordThat(new UserPasswordRecovered($this->id()->toString(), $encodedPassword));
    }

    /**
     * @return string
     */
    protected function aggregateId(): string
    {
        return $this->id()->toString();
    }

    /**
     * @param UserWasCreated $event
     *
     * @throws InvalidArgumentException
     */
    protected function whenUserWasCreated(UserWasCreated $event): void
    {
        $this->id = UserId::createFromString($event->id());
        $this->email = new Email($event->email());
        $this->name = new Name($event->firstName(), $event->lastName());
        $this->password = $event->password();
        $this->salt = $event->salt();
        $this->roles = $event->roles();
        $this->status = $event->status();
    }

    /**
     * @param UserPasswordRecoveryInitiated $event
     *
     * @throws InvalidArgumentException
     */
    protected function whenUserPasswordRecoveryInitiated(UserPasswordRecoveryInitiated $event): void
    {
        $this->status = new Status(
            $this->status()->isAccountExpired(),
            $this->status()->isAccountLocked(),
            true,
            $this->status()->isEnabled()
        );

        $this->recovery = Recovery::whenUserPasswordRecoveryInitiated($event);
    }

    /**
     * @param UserPasswordRecovered $event
     */
    protected function whenUserPasswordRecovered(UserPasswordRecovered $event): void
    {
        $this->password = $event->password();
        $this->status = new Status(
            $this->status()->isAccountExpired(),
            $this->status()->isAccountLocked(),
            false,
            $this->status()->isEnabled()
        );
        $this->recovery = null;
    }

    /**
     * @param AggregateChanged $event
     *
     * @throws InvalidArgumentException
     */
    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof UserWasCreated) {
            $this->whenUserWasCreated($event);

            return;
        }

        if ($event instanceof UserPasswordRecoveryInitiated) {
            $this->whenUserPasswordRecoveryInitiated($event);

            return;
        }

        if ($event instanceof UserPasswordRecovered) {
            $this->whenUserPasswordRecovered($event);

            return;
        }

        throw new InvalidArgumentException(
            sprintf('event "%s" not supported', get_class($event))
        );
    }
}
