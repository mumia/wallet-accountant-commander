<?php

namespace WalletAccountant\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WalletAccountant\Common\Controller\AbstractController;
use WalletAccountant\Common\Exceptions\CommandDispatchException;
use WalletAccountant\Domain\User\Command\RecoverUserPassword;
use WalletAccountant\Domain\User\Command\UserInitiatePasswordRecovery;

/**
 * UserController
 */
class UserController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws CommandDispatchException
     */
    public function initiatePasswordRecovery(Request $request): JsonResponse
    {
        $this->dispatchCommand(
            UserInitiatePasswordRecovery::class,
            [
                UserInitiatePasswordRecovery::EMAIL => $request->request->get(UserInitiatePasswordRecovery::EMAIL)
            ]
        );

        return new JsonResponse(['message' => 'user password recovery initiated']);
    }

    /**
     * @param Request $request
     * @param string  $code
     *
     * @return JsonResponse
     *
     * @throws CommandDispatchException
     */
    public function recoverPassword(Request $request, string $code): JsonResponse
    {
        $this->dispatchCommand(
            RecoverUserPassword::class,
            [
                RecoverUserPassword::CODE => $code,
                RecoverUserPassword::PASSWORD => $request->request->get(RecoverUserPassword::PASSWORD),
                RecoverUserPassword::REPEAT_PASSWORD => $request->request->get(RecoverUserPassword::REPEAT_PASSWORD)
            ]
        );

        return new JsonResponse(['message' => 'user password recovered']);
    }
}
