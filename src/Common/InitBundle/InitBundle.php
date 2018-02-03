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
use WalletAccountant\Infrastructure\MongoDB\Types\DateTimeType;
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
        Type::registerType('datetime', DateTimeType::class);
        Type::registerType('userid', UserIdType::class);
    }
}
