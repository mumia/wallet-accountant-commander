<?php

namespace WalletAccountant\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use WalletAccountant\Document\Realtime\UserEmails;
use WalletAccountant\Infrastructure\MongoDB\UserEmailsRepository;

/**
 * TestController
 */
class TestController extends Controller
{
    /**
     * @var UserEmailsRepository
     */
    private $userEmailsRepository;

    /**
     * @param UserEmailsRepository $userEmailsRepository
     */
    public function __construct(UserEmailsRepository $userEmailsRepository)
    {
        $this->userEmailsRepository = $userEmailsRepository;
    }

    /**
     * @return Response
     */
    public function test(): Response
    {
        $val = time();
        $val = sprintf('teste_%d@email.com', $val);
        var_dump($val);

        $this->userEmailsRepository->emailExists($val);

        $email = new UserEmails();
        $email->email = $val;

        $this->userEmailsRepository->persist($email);

        $this->userEmailsRepository->emailExists($val);

        return new Response('Tested!');
    }
}
