<?php

namespace WalletAccountant\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Common\Controller\AbstractController;
use WalletAccountant\Common\Exceptions\CommandDispatchException;
use WalletAccountant\Domain\Account\Command\AddMovementToLedger;
use WalletAccountant\Domain\Account\Command\CreateAccount;
use WalletAccountant\Domain\Account\Command\UpdateAccountOwner;

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
            $this->extractPayloadFromRequest(
                $request,
                [CreateAccount::ID => $id],
                [CreateAccount::BANK_ID , CreateAccount::OWNER_ID , CreateAccount::IBAN]
            )
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
            $this->extractPayloadFromRequest(
                $request,
                [UpdateAccountOwner::ID => $id],
                [UpdateAccountOwner::OWNER_ID]
            )
        );

        return new JsonResponse(['message' => 'account owner updated']);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     *
     * @throws CommandDispatchException
     */
    public function addMovementToLedger(Request $request, string $id): Response
    {
        $this->dispatchCommand(
            AddMovementToLedger::class,
            $this->extractPayloadFromRequest(
                $request,
                [AddMovementToLedger::ACCOUNT_ID => $id, AddMovementToLedger::ID => Uuid::uuid4()->toString()],
                [
                    AddMovementToLedger::TYPE,
                    AddMovementToLedger::AMOUNT,
                    AddMovementToLedger::DESCRIPTION,
                    AddMovementToLedger::PROCESSED_ON
                ]
            )
        );

        return new JsonResponse(['message' => 'movement added to account ledger']);
    }
}
