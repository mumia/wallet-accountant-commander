<?php
declare(strict_types=1);

namespace WalletAccountant\Tests\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * TestCaseTest
 */
final class TestCaseTest extends WebTestCase
{
    public function testTestsRunning(): void
    {
        $expected = 'tests are working';

        $this->assertEquals('tests are working', $expected);
    }
}
