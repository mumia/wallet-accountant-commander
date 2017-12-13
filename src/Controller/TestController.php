<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * TestController
 */
class TestController extends Controller
{
    /**
     * @return Response
     */
    public function test(): Response
    {
        return new Response('Tested!');
    }
}
