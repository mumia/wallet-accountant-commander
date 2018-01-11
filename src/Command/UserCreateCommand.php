<?php

namespace WalletAccountant\Command;

use Prooph\Bundle\ServiceBus\CommandBus;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\Exception\CommandDispatchException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WalletAccountant\Domain\User\Command\CreateUser;
use WalletAccountant\Domain\User\UserCommandsEnum;

/**
 * UserCreateCommand
 */
class UserCreateCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @param CommandBus     $commandBus
     * @param MessageFactory $messageFactory
     *
     * @throws LogicException
     */
    public function __construct(CommandBus $commandBus, MessageFactory $messageFactory)
    {
        parent::__construct(null);

        $this->commandBus = $commandBus;
        $this->messageFactory = $messageFactory;
    }

    public function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Add a new user (will be forced to change password on first login)')
            ->addArgument('email', InputArgument::REQUIRED, 'Email address (will be user as the username)')
            ->addArgument('first name', InputArgument::REQUIRED, 'User\'s first name')
            ->addArgument('last name', InputArgument::REQUIRED, 'User\'s last name')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws CommandDispatchException
     * @throws InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $payload = [
            'id' => Uuid::uuid4()->toString(),
            'email' => $input->getArgument('email'),
            'first_name' => $input->getArgument('first name'),
            'last_name' => $input->getArgument('last name')
        ];

        $command = $this->messageFactory->createMessageFromArray(CreateUser::class, ['payload' => $payload]);

        try {
            $this->commandBus->dispatch($command);
        } catch (CommandDispatchException $exception) {
            $io->error(
                sprintf('Failed to create User with email %s;', $payload['email'])
            );

            throw $exception;
        }

        $io->success(
            sprintf('User created %s %s %s', $payload['email'], $payload['first_name'], $payload['last_name'])
        );
    }
}
