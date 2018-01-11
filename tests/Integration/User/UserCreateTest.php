<?php

namespace WalletAccountant\Tests\Integration\User;

use function get_class;
use function sprintf;
use Exception;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * UserCreateTest
 */
class UserCreateTest extends UserIntegrationTestCase
{
    /**
     * @throws DBALException
     */
    public function testCreate()
    {
        $this->assertNotExists();

        $commandTester = $this->createUser();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $expectedSuccessMessage = sprintf('User created %s %s %s', self::EMAIL, self::FIRST_NAME, self::LAST_NAME);
        $this->assertContains($expectedSuccessMessage, $output);

        $this->assertExists();

        $this->assertProjectionIsExpected(
            ['_id' => self::EMAIL, 'name' => ['first' => 'firstname', 'last' => 'lastname']]
        );
    }

    /**
     * @throws DBALException
     */
    public function testCreateWithExistingEmail()
    {
        $expectedException = 'WalletAccountant\Exceptions\User\UserEmailNotUnique';
        $expectedExceptionMessage = sprintf('User with email "%s" already exists', self::EMAIL);

        $this->assertExists();

        try {
            $this->createUser();
        } catch (Exception $exception) {
            $this->assertEquals($expectedException, get_class($exception->getPrevious()));
            $this->assertEquals($expectedExceptionMessage, $exception->getPrevious()->getMessage());

            return;
        }

        $this->fail('Should have thrown an exception ');
    }

    /**
     * @return CommandTester
     */
    private function createUser(): CommandTester
    {
        $command = $this->container->get('console.command.walletaccountant_command_usercreatecommand');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'email' => self::EMAIL,
                'first name' => self::FIRST_NAME,
                'last name' => self::LAST_NAME
            ]
        );

        // Run projection
        $projectionRunner = $this->container->get('walletaccountant.projection_runner.user');
        $projectionRunner->run();

        return $commandTester;
    }
}
