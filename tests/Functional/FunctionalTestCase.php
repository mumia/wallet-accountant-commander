<?php

namespace WalletAccountant\Tests\Functional;

use function json_decode;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * FunctionalTestCase
 */
abstract class FunctionalTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        static ::bootKernel();
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return JsonResponse
     */
    public function login(string $email, string $password): JsonResponse
    {
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/login', ['email' => $email, 'password' => $password]);

        $response = $client->getResponse();

        if (!$response instanceof JsonResponse) {
            $this->fail('Did not receive a login response');
        }

        return $response;
    }

    /**
     * @param string $projection
     */
    protected function runProjection(string $projection): void
    {
        $projectionRunner = static::$container->get(sprintf('walletaccountant.projection_runner.%s', $projection));
        $projectionRunner->run();
    }
}
