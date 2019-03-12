<?php

namespace WalletAccountant\Controller;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * SecurityController
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     */
    public function login(Request $request): void
    {
        throw new RuntimeException('This should be handled by the firewall');
    }
}
