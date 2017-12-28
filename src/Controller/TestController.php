<?php

namespace WalletAccountant\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Document\User;
use WalletAccountant\Infrastructure\MongoDB\UserRepository;

/**
 * TestController
 */
class TestController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return Response
     */
    public function test(): Response
    {
        $val = time();
        $val = sprintf('teste_%d@email.com', $val);
        var_dump($val);

        $this->userRepository->emailExists($val);

        $email = new User();
        $email->email = $val;

        $this->userRepository->persist($email);

        $this->userRepository->emailExists($val);

        return new Response('Tested!');
    }
}
