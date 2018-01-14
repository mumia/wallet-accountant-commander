<?php

namespace WalletAccountant\Domain\User\Recovery;

use function get_class;
use Respect\Validation\Validator;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Domain\Common\ValueObjectInterface;
use WalletAccountant\Domain\User\Event\UserPasswordRecoveryInitiated;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * Recovery
 */
final class Recovery implements ValueObjectInterface
{
    private const MIN_HOURS = 2;
    private const DEFAULT_HOURS = 360;
    private const MAX_HOURS = 480;

    /**
     * Automatically generated and used to recover account.
     *
     * @var string
     */
    private $code;

    /**
     * @var DateTime
     */
    private $expiresOn;

    /**
     * @param string   $code
     * @param DateTime $expiresOn
     */
    private function __construct(string $code, DateTime $expiresOn)
    {
        $this->code = $code;
        $this->expiresOn = $expiresOn;
    }

    /**
     * @param string   $code
     * @param DateTime $expiresOn
     *
     * @return Recovery
     *
     * @throws InvalidArgumentException
     */
    public static function createNew(string $code, DateTime $expiresOn): self
    {
        if (!Validator::stringType()->notEmpty()->validate($code)) {
            throw new InvalidArgumentException('invalid user password recovery code');
        }

        $min = DateTime::now()->addHours(self::MIN_HOURS);
        $max = DateTime::now()->addHours(self::MAX_HOURS);
        if ($expiresOn->lt($min) || $expiresOn->gt($max)) {
            throw new InvalidArgumentException('invalid user password expires on argument');
        }

        return new self($code, $expiresOn);
    }

    /**
     * @param UserPasswordRecoveryInitiated $event
     *
     * @return Recovery
     */
    public static function whenUserPasswordRecoveryInitiated(UserPasswordRecoveryInitiated $event): self
    {
        return new self($event->code(), $event->expiresOn());
    }

    /**
     * @param string|null $code
     *
     * @return Recovery
     *
     * @throws InvalidArgumentException
     */
    public static function create(string $code = null): self
    {
        $code = $code ?? self::createCode();
        $expiresOn = self::createDefaultExpiresOn();

        return self::createNew($code, $expiresOn);
    }

    /**
     * @return string
     */
    public static function createCode(): string
    {
        return substr(sha1(mt_rand()), 0, 16);
    }

    /**
     * @return DateTime
     */
    public static function createDefaultExpiresOn(): DateTime
    {
        return DateTime::now()->addHours(self::DEFAULT_HOURS);
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return DateTime
     */
    public function expiresOn(): DateTime
    {
        return $this->expiresOn;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expiresOn()->gte(DateTime::now());
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function validateRecovery(string $code): bool
    {
        return $this->isExpired() && $this->code() === $code;
    }

    /**
     * {@inheritdoc}
     */
    public function sameValueAs(ValueObjectInterface $that): bool
    {
        return $that instanceof self
            && get_class($this) === get_class($that)
            && $this->code() === $that->code()
            && $this->expiresOn()->sameValueAs($that->expiresOn());
    }
}
