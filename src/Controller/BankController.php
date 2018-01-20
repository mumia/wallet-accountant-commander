<?php

namespace WalletAccountant\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Common\Controller\AbstractController;
use WalletAccountant\Common\Exceptions\CommandDispatchException;
use WalletAccountant\Domain\Bank\Command\CreateBank;
use WalletAccountant\Domain\Bank\Command\UpdateBank;

/**
 * BankController
 */
class BankController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws CommandDispatchException
     */
    public function createBank(Request $request): Response
    {
        $id = Uuid::uuid4()->toString();

        $this->dispatchCommand(
            CreateBank::class,
            [
                CreateBank::ID => $id,
                CreateBank::NAME => $request->request->get(CreateBank::NAME)
            ]
        );

        return new JsonResponse(['message' => 'bank created', CreateBank::ID => $id]);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     *
     * @throws CommandDispatchException
     */
    public function updateBank(Request $request, string $id): Response
    {
        $this->dispatchCommand(
            UpdateBank::class,
            [
                UpdateBank::ID => $id,
                UpdateBank::NAME => $request->request->get(UpdateBank::NAME)
            ]
        );

        return new JsonResponse(['message' => 'bank updated']);
    }
}
