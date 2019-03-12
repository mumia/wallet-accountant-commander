<?php

namespace WalletAccountant\Tests\Functional\Authenticator;

use Doctrine\DBAL\DBALException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Tests\Functional\Fixtures\Authenticator\AuthenticatorFixtures;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPassword;
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

        $this->loadFixtures();

        $response = $this->login(UserWithPassword::EMAIL, UserWithPassword::PASSWORD);

        $token = json_decode($response->getContent(), true);

        $this->assertFalse(isset($token['error']));

        $encoder = $this->container->get('test.jwt.encoder');
        $decodedToken = $encoder->decode($token['token']);

        $expectedToken = [
            'exp' => DateTime::now()->addDays(10)->getTimestamp(),
            'email' => UserWithPassword::EMAIL,
            'name' => [
                'first' => UserWithPassword::FIRST_NAME,
                'last' => UserWithPassword::LAST_NAME
            ],
            'iat' => DateTime::now()->getTimestamp(),
            'sub' => UserWithPassword::EVENT_AGGREGATE_ID
        ];

        $this->assertEquals($expectedToken, $decodedToken);
    }
}
