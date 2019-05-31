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

use Doctrine\ODM\MongoDB\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WalletAccountant\Infrastructure\MongoDB\Annotations\BankIdAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\DateTimeAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\EmailAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Annotations\UserIdAnnotation;
use WalletAccountant\Infrastructure\MongoDB\Types\BankIdType;
use WalletAccountant\Infrastructure\MongoDB\Types\DateTimeType;
use WalletAccountant\Infrastructure\MongoDB\Types\EmailType;
use WalletAccountant\Infrastructure\MongoDB\Types\UserIdType;

/**
 * InitBundle
 */
class InitBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        Type::registerType(DateTimeAnnotation::TYPE, DateTimeType::class);
        Type::registerType(UserIdAnnotation::TYPE, UserIdType::class);
        Type::registerType(BankIdAnnotation::TYPE, BankIdType::class);
        Type::registerType(EmailAnnotation::TYPE, EmailType::class);
    }
}
