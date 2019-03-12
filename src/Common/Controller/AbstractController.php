<?php

namespace WalletAccountant\Common\Controller;

use Prooph\Bundle\ServiceBus\CommandBus;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\Exception\CommandDispatchException as BaseCommandDispatchException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WalletAccountant\Common\Exceptions\CommandDispatchException;

/**
 * AbstractController
 */
class AbstractController extends Controller
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
     * @param CommandBus        $commandBus
     * @param MessageFactory    $messageFactory
     */
    public function __construct(CommandBus $commandBus, MessageFactory $messageFactory)
    {
        $this->commandBus = $commandBus;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @param string $class
     * @param array  $payload
     *
     * @throws CommandDispatchException
     */
    protected function dispatchCommand(string $class, array $payload = [])
    {
        $command = $this->messageFactory->createMessageFromArray($class, ['payload' => $payload]);

        try {
            $this->commandBus->dispatch($command);
        } catch (BaseCommandDispatchException $exception) {
            throw CommandDispatchException::withClassAndPayload(
                $class,
                $payload,
                $exception->getPrevious()
            );
        }
    }

    /**
     * @param Request $request
     * @param array   $data
     * @param array   $keys
     *
     * @return array
     */
    protected function extractPayloadFromRequest(Request $request, array $data, array $keys): array
    {
        foreach ($keys as $key) {
            $data[$key] = $request->request->get($key);
        }

        return $data;
    }
}
