<?php

namespace WalletAccountant\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function var_dump;
use WalletAccountant\Common\Controller\AbstractController;
use WalletAccountant\Common\Exceptions\CommandDispatchException;
use WalletAccountant\Domain\Account\Command\CreateAccount;
use WalletAccountant\Domain\Account\Command\UpdateAccountOwner;
use WalletAccountant\Domain\Bank\Command\CreateBank;
use WalletAccountant\Domain\Bank\Command\UpdateBank;

/**
 * AccountController
 */
class AccountController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws CommandDispatchException
     */
    public function createAccount(Request $request): Response
    {
        $id = Uuid::uuid4()->toString();

        $this->dispatchCommand(
            CreateAccount::class,
            [
                CreateAccount::ID => $id,
                CreateAccount::BANK_ID => $request->request->get(CreateAccount::BANK_ID),
                CreateAccount::OWNER_ID => $request->request->get(CreateAccount::OWNER_ID),
                CreateAccount::IBAN => $request->request->get(CreateAccount::IBAN)
            ]
        );

        return new JsonResponse(['message' => 'account created', CreateAccount::ID => $id], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     *
     * @throws CommandDispatchException
     */
    public function updateAccountOwner(Request $request, string $id): Response
    {
        $this->dispatchCommand(
            UpdateAccountOwner::class,
            [
                UpdateAccountOwner::ID => $id,
                UpdateAccountOwner::OWNER_ID => $request->request->get(UpdateAccountOwner::OWNER_ID)
            ]
        );

        return new JsonResponse(['message' => 'account owner updated']);
    }
}
