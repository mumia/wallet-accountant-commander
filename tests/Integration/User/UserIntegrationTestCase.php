<?php

namespace WalletAccountant\Tests\Integration\User;

use Exception;
use Symfony\Component\Console\Tester\CommandTester;
use WalletAccountant\Document\User;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Tests\Integration\IntegrationTestCase;

/**
 * UserIntegrationTestCase
 */
abstract class UserIntegrationTestCase extends IntegrationTestCase
{
    protected const EMAIL = 'valid@email.com';
    protected const FIRST_NAME = 'firstname';
    protected const LAST_NAME = 'lastname';
    protected const PROJECTION_DOCUMENT_NAME = User::class;
    protected const PROJECTION_NAME = 'walletaccountant.projection_runner.user';

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct(self::PROJECTION_DOCUMENT_NAME, $name, $data, $dataName);
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @param UserId $userId
     * @param User $expectedProjection
     */
    protected function assertProjectionIsExpected(UserId $userId, User $expectedProjection): void
    {
        $actualProjection = $this->projectionRepository->find($userId->toString());
        $this->assertEquals($expectedProjection, $actualProjection);
    }

    /**
     * @return CommandTester
     * @throws Exception
     */
    protected function createUser(): CommandTester
    {
        $command = static::$container->get('console.command.public_alias.WalletAccountant\Command\UserCreateCommand');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'email' => self::EMAIL,
                'first name' => self::FIRST_NAME,
                'last name' => self::LAST_NAME
            ]
        );

        return $commandTester;
    }
}
