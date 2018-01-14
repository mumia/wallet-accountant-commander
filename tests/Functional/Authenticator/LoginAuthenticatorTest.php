<?php

namespace WalletAccountant\Tests\Functional\Authenticator;

use Doctrine\DBAL\DBALException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Tests\Functional\Fixtures\Authenticator\AuthenticatorFixtures;
use WalletAccountant\Tests\Functional\FunctionalTestCase;

/**
 * LoginAuthenticatorTest
 */
class LoginAuthenticatorTest extends FunctionalTestCase
{
    /**
     * @throws DBALException
     * @throws JWTDecodeFailureException
     */
    public function testLogin()
    {
        DateTime::setTestNow(DateTime::now());

        $fixtures = $this->container->get('fixtures.loader.authenticator');
        $fixtures->userWithPassword();

        $response = $this->login(AuthenticatorFixtures::EMAIL, AuthenticatorFixtures::PASSWORD);

        $token = json_decode($response->getContent(), true);

        $decodedToken = $fixtures->decodeJWToken($token['token']);

        $expectedToken = [
            'exp' => DateTime::now()->addDays(10)->getTimestamp(),
            'email' => AuthenticatorFixtures::EMAIL,
            'name' => [
                'first' => AuthenticatorFixtures::FIRST_NAME,
                'last' => AuthenticatorFixtures::LAST_NAME
            ],
            'iat' => DateTime::now()->getTimestamp()
        ];

        $this->assertEquals($expectedToken, $decodedToken);
    }
}
