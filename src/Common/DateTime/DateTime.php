<?php

namespace WalletAccountant\Common\DateTime;

use Cake\Chronos\Chronos;
use DateTimeImmutable;
use function get_class;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Domain\Common\ValueObjectInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * DateTime
 */
class DateTime extends Chronos implements ValueObjectInterface
{
    public const DATE_FORMAT = 'Y-m-d';
    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    public const DATE_TIME_MICRO_FORMAT = 'Y-m-d H:i:s.u';
    public const DATE_TIME_MICRO_FULL_FORMAT = 'Y-m-d\TH:i:s.uP';

    /**
     * @param DateTimeImmutable $dateTimeImmutable
     *
     * @return DateTime
     *
     * @throws InvalidArgumentException
     */
    public static function createFromDateImmutable(DateTimeImmutable $dateTimeImmutable): self
    {
        try {
            return self::createFromFormat(
                self::DATE_TIME_MICRO_FULL_FORMAT,
                $dateTimeImmutable->format(self::DATE_TIME_MICRO_FULL_FORMAT)
            );
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * @param string $date
     *
     * @return DateTime
     *
     * @throws InvalidArgumentException
     */
    public static function createFromDateFormat(string $date): self
    {
        try {
            return self::createFromFormat(self::DATE_FORMAT, $date);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * @param string $date
     *
     * @return DateTime
     *
     * @throws InvalidArgumentException
     */
    public static function createFromDateTimeFormat(string $date): self
    {
        try {
            return self::createFromFormat(self::DATE_TIME_FORMAT, $date);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * @param string $date
     *
     * @return DateTime
     *
     * @throws InvalidArgumentException
     */
    public static function createFromDateTimeMicroFormat(string $date): self
    {
        try {
            return self::createFromFormat(self::DATE_TIME_MICRO_FORMAT, $date);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * @param string $date
     *
     * @return DateTime
     *
     * @throws InvalidArgumentException
     */
    public static function createFromDateTimeMicroFullFormat(string $date): self
    {
        try {
            return self::createFromFormat(self::DATE_TIME_MICRO_FULL_FORMAT, $date);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * @return string
     */
    public function toDate(): string
    {
        return $this->format(self::DATE_FORMAT);
    }

    /**
     * @return string
     */
    public function toDateTime(): string
    {
        return $this->format(self::DATE_TIME_FORMAT);
    }

    /**
     * @return string
     */
    public function toDateTimeMicro(): string
    {
        return $this->format(self::DATE_TIME_MICRO_FORMAT);
    }

    /**
     * @return string
     */
    public function toDateTimeMicroFull(): string
    {
        return $this->format(self::DATE_TIME_MICRO_FULL_FORMAT);
    }

    /**
     * {@inheritdoc}
     */
    public function sameValueAs(ValueObjectInterface $that): bool
    {
        return $that instanceof self && get_class($this) === get_class($that) && $this->eq($that);
    }
}
