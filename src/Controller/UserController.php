<?php

namespace WalletAccountant\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Common\Controller\AbstractController;
use WalletAccountant\Common\Exceptions\CommandDispatchException;
use WalletAccountant\Domain\User\Command\RecoverUserPassword;

/**
 * UserController
 */
class UserController extends AbstractController
{
    /**
     * @param Request $request
     * @param string  $code
     *
     * @return Response
     *
     * @throws CommandDispatchException
     */
    public function recoverPassword(Request $request, string $code): Response
    {
        $this->dispatchCommand(
            RecoverUserPassword::class,
            [
                'code' => $code,
                'password' => $request->request->get('password'),
                'repeat_password' => $request->request->get('repeat_password')
            ]
        );

        return new JsonResponse(['message' => 'user password recovered']);
    }
}
