<?php

namespace WalletAccountant\Tests\Functional\Bank;

use Doctrine\DBAL\DBALException;
use WalletAccountant\Document\Bank;
use WalletAccountant\Tests\Functional\Fixtures\Bank\BankFixtures;
use WalletAccountant\Tests\Functional\FunctionalTestCase;

/**
 * BankTest
 */
class BankTest extends FunctionalTestCase
{
    /**
     * @throws DBALException
     */
    public function testCreateBank(): void
    {
        $bankFixtures = $this->container->get('fixtures.loader.bank');
        $bankFixtures->createBank();

        $expectedBank = new Bank(BankFixtures::ID, BankFixtures::BANK_NAME);
        $actualBank = $bankFixtures->getCurrentBank();

        $this->assertEquals($expectedBank, $actualBank);
    }

    /**
     * @throws DBALException
     */
    public function testUpdateBank(): void
    {
        $bankFixtures = $this->container->get('fixtures.loader.bank');
        $bankFixtures->createBank();
        $bankFixtures->updateBank();

        $expectedBank = new Bank(BankFixtures::ID, BankFixtures::BANK_NAME_UPDATE);
        $actualBank = $bankFixtures->getCurrentBank();

        $this->assertEquals($expectedBank, $actualBank);
    }
}
