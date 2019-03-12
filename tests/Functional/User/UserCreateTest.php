<?php

namespace WalletAccountant\Tests\Functional\User;

use Symfony\Component\Console\Tester\CommandTester;
use WalletAccountant\Document\User\Status;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Tests\Functional\FunctionalTestCase;

/**
 * UserCreateTest
 */
class UserCreateTest extends FunctionalTestCase
{
    public function testUserCreateCommand(): void
    {
        $email = 'testemail@fakedomain.tld';
        $firstName = 'first_name';
        $lastName = 'last_name';

        $command = $this->container->get('console.command.walletaccountant_command_usercreatecommand');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['email' => $email, 'first name' => $firstName, 'last name' => $lastName]);

        $actualOutput = $commandTester->getDisplay();
        $expectedOutput = sprintf('User created %s %s %s', $email, $firstName, $lastName);
        $this->assertContains($expectedOutput, $actualOutput);

        $this->runProjection('user');

        $userProjectionRepository = $this->container->get('test.user_projection_repository');
        $actualUser = $userProjectionRepository->getByEmail(Email::createFromString($email));

        $expectedStatus = new Status(false, false, true, true);

        $this->assertEquals($email, $actualUser->getEmail());
        $this->assertEquals($firstName, $actualUser->getName()->getFirst());
        $this->assertEquals($lastName, $actualUser->getName()->getLast());
        $this->assertTrue($actualUser->hasRecovery());
        $this->assertEquals($expectedStatus, $actualUser->getStatus());
    }
}
