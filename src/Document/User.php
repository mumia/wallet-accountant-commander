<?php

namespace WalletAccountant\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\User\Name;
use WalletAccountant\Document\User\Recovery;
use WalletAccountant\Document\User\Status;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * User
 *
 * @MongoDB\Document
 */
final class User implements AdvancedUserInterface
{
    /**
     * @var UserId
     *
     * @MongoDB\Id(strategy="none", type="userid")
     */
    private $id;

    /**
     * @var Email
     *
     * @MongoDB\Field(type="email")
     */
    private $email;

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
     * @param UserId        $id
     * @param Email         $email
     * @param Name          $name
     * @param array         $roles
     * @param string        $password
     * @param string        $salt
     * @param Status        $status
     * @param Recovery|null $recovery
     */
    public function __construct(
        UserId $id,
        Email $email,
        Name $name,
        array $roles,
        string $password,
        string $salt,
        Status $status,
        Recovery $recovery = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->roles = $roles;
        $this->password = $password;
        $this->salt = $salt;
        $this->status = $status;
        $this->recovery = $recovery;
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return Email
     *
     * @throws InvalidArgumentException
     */
    public function getEmail(): Email
    {
        return $this->email;
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
     * @param string   $recoveryCode
     * @param DateTime $expiresOn
     */
    public function initiatePasswordRecovery(string $recoveryCode, DateTime $expiresOn): void
    {
        $this->status = new Status(
            $this->getStatus()->isAccountExpired(),
            $this->getStatus()->isAccountLocked(),
            true,
            $this->getStatus()->isEnabled()
        );

        $this->recovery = new Recovery($recoveryCode, $expiresOn);
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->getEmail()->toString();
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
