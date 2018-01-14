<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use WalletAccountant\Document\User\Name;
use WalletAccountant\Document\User\Recovery;
use WalletAccountant\Document\User\Status;

/**
 * User
 *
 * @MongoDB\Document
 */
final class User implements AdvancedUserInterface
{
    /**
     * @var string
     *
     * @MongoDB\Id(strategy="none")
     */
    private $email;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", name="aggregate_id")
     */
    private $aggregateId;

    /**
     * @var Name
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\User\Name")
     */
    private $name;

    /**
     * @var array
     *
     * @MongoDB\Field(type="collection")
     */
    private $roles;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    private $salt;

    /**
     * @var Status
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\User\Status")
     */
    private $status;

    /**
     * @var Recovery
     *
     * @MongoDB\EmbedOne(targetDocument="WalletAccountant\Document\User\Recovery")
     */
    private $recovery;

    /**
     * @param string        $email
     * @param string        $aggregateId
     * @param Name          $name
     * @param array         $roles
     * @param string        $password
     * @param string        $salt
     * @param Status        $status
     * @param Recovery|null $recovery
     */
    public function __construct(
        string $email,
        string $aggregateId,
        Name $name,
        array $roles,
        string $password,
        string $salt,
        Status $status,
        Recovery $recovery = null
    ) {
        $this->email = $email;
        $this->aggregateId = $aggregateId;
        $this->name = $name;
        $this->roles = $roles;
        $this->password = $password;
        $this->salt = $salt;
        $this->status = $status;
        $this->recovery = $recovery;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * @return Name
     */
    public function getName(): Name
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
     * @param string $password
     */
    public function recoverPassword(string $password)
    {
        $this->password = $password;
        $this->status = new Status(
            $this->getStatus()->isAccountExpired(),
            $this->getStatus()->isAccountLocked(),
            false,
            $this->getStatus()->isEnabled()
        );
        $this->recovery = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function hasRecovery(): bool
    {
        return $this->getRecovery() instanceof Recovery;
    }

    /**
     * @return null|Recovery
     */
    public function getRecovery(): ?Recovery
    {
        return $this->recovery;
    }

    /**
     * @param Recovery $recovery
     */
    public function setRecovery(Recovery $recovery): void
    {
        $this->recovery = $recovery;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired(): bool
    {
        return !$this->status->isAccountExpired();
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked(): bool
    {
        return !$this->status->isAccountLocked();
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired(): bool
    {
        return !$this->status->isCredentialsExpired();
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->status->isEnabled();
    }
}
