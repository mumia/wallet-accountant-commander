<?php

namespace WalletAccountant\Common\Controller;

use NilPortugues\Symfony\JsonApiBundle\Serializer\JsonApiResponseTrait;
use NilPortugues\Symfony\JsonApiBundle\Serializer\JsonApiSerializer;
use Prooph\Bundle\ServiceBus\CommandBus;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\Exception\CommandDispatchException as BaseCommandDispatchException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WalletAccountant\Common\Exceptions\CommandDispatchException;

/**
 * AbstractQueryController
 */
class AbstractQueryController extends Controller
{
}
