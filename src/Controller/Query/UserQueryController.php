<?php

namespace WalletAccountant\Controller\Query;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Common\Controller\AbstractQueryController;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Document\User;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Mediator\UserMediator;

/**
 * UserQueryController
 */
class UserQueryController extends AbstractQueryController
{
    /**
     * @var UserMediator
     */
    private $userMediator;

    /**
     * @param UserMediator $userMediator
     */
    public function __construct(UserMediator $userMediator)
    {
        $this->userMediator = $userMediator;
    }

    /**
     * @param string $id
     *
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"Default"})
     *
     * @return User
     *
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function getById(string $id): User
    {
        return $this->userMediator->getById(UserId::createFromString($id));
    }
}
