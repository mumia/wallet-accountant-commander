<?php

/*
 * This file is part of the Doctrine Bundle
 *
 * The code was originally distributed inside the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Doctrine Project, Benjamin Eberlei <kontakt@beberlei.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WalletAccountant\Common\InitBundle;

use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WalletAccountant\Domain\Account\Ledger\Id\MovementId;
use WalletAccountant\Infrastructure\MongoDB\Annotations\AccountIdAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\CurrencyCodeAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\DateTimeAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\BankIdAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\EmailAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\IbanAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\MovementIdAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\MovementTypeAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\UserIdAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Types\AccountIdType;
use WalletAccountant\Infrastructure\MongoDB\Types\BankIdType;
use WalletAccountant\Infrastructure\MongoDB\Types\CurrencyCodeType;
use WalletAccountant\Infrastructure\MongoDB\Types\DateTimeType;
use WalletAccountant\Infrastructure\MongoDB\Types\EmailType;
use WalletAccountant\Infrastructure\MongoDB\Types\IbanType;
use WalletAccountant\Infrastructure\MongoDB\Types\MovementIdType;
use WalletAccountant\Infrastructure\MongoDB\Types\MovementTypeType;
use WalletAccountant\Infrastructure\MongoDB\Types\UserIdType;

/**
 * InitBundle
 */
class InitBundle extends Bundle
{
    /**
     * {@inheritDoc}
     *
     * @throws MappingException
     */
    public function boot()
    {
        Type::registerType(DateTimeAnnotation::TYPE, DateTimeType::class);
        Type::registerType(UserIdAnnotation::TYPE, UserIdType::class);
        Type::registerType(BankIdAnnotation::TYPE, BankIdType::class);
        Type::registerType(AccountIdAnnotation::TYPE, AccountIdType::class);
        Type::registerType(EmailAnnotation::TYPE, EmailType::class);
        Type::registerType(IbanAnnotation::TYPE, IbanType::class);
        Type::registerType(MovementIdAnnotation::TYPE, MovementIdType::class);
        Type::registerType(MovementTypeAnnotation::TYPE, MovementTypeType::class);
        Type::registerType(CurrencyCodeAnnotation::TYPE, CurrencyCodeType::class);
    }
}
