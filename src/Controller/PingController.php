<?php

namespace WalletAccountant\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * PingController
 */
class PingController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function ping(): JsonResponse
    {
        return new JsonResponse(['message' => 'pong']);
    }

    /**
     * @return JsonResponse
     */
    public function pingAuthenticated(): JsonResponse
    {
        return new JsonResponse(['message' => 'pong authenticated']);
    }
}
