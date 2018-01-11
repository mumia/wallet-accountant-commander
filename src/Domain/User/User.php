<?php

namespace WalletAccountant\Domain\User;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Event\UserWasCreated;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;
use Symfony\Component\Security\Core\User\UserInterface;
use WalletAccountant\Exceptions\InvalidArgumentException;

/**
 * User
 */
final class User extends AggregateRoot implements AdvancedUserInterface
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
     * @param UserId $id
     * @param Email  $email
     * @param Name   $name
     * @param string $password
     * @param string $salt
     * @param array  $roles
     */
    protected function __construct(UserId $id, Email $email, Name $name, string $password, string $salt, array $roles)
    {
        parent::__construct();

        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
    }

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
        $user = new self($id, $email, $name, '', '', []);

        $userCreated = new UserWasCreated(
            $user->id()->toString(),
            $user->email()->toString(),
            $user->name()->first(),
            $user->name()->last()
        );
        $user->recordThat($userCreated);

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
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->email->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        // TODO: Implement isAccountNonExpired() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        // TODO: Implement isAccountNonLocked() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        // TODO: Implement isCredentialsNonExpired() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
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
    }
}
