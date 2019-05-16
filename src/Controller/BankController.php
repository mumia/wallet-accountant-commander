<?php

namespace WalletAccountant\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WalletAccountant\Common\Controller\AbstractController;
use WalletAccountant\Common\Exceptions\CommandDispatchException;
use WalletAccountant\Domain\Bank\Command\CreateBank;

class BankController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws CommandDispatchException
     */
    public function createBank(Request $request): JsonResponse
    {
        $this->dispatchCommand(
            CreateBank::class,
            [
                CreateBank::OWNER_ID => $request->request->get(CreateBank::OWNER_ID),
                CreateBank::NAME => $request->request->get(CreateBank::NAME)
            ]
        );

        return new JsonResponse(['message' => 'bank created']);
    }
}
