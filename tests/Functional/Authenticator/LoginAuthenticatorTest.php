<?php

namespace WalletAccountant\Tests\Functional\Authenticator;

use Doctrine\DBAL\DBALException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use stdClass;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Tests\Functional\Fixtures\User\UserFixtures;
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

        /** @var UserFixtures $fixtures */
        $fixtures = static::$container->get('fixtures.loader.user');
        $fixtures->userWithPassword();

        $response = $this->login(UserFixtures::EMAIL, UserFixtures::PASSWORD);

        $token = json_decode($response->getContent(), true);

        $this->assertFalse(isset($token['error']));

        $decodedToken = $fixtures->decodeJWToken($token['token']);

        $name = new stdClass();
        $name->first = UserFixtures::FIRST_NAME;
        $name->last = UserFixtures::LAST_NAME;

        $expectedToken = [
            'exp' => DateTime::now()->addDays(10)->getTimestamp(),
            'email' => UserFixtures::EMAIL,
            'name' => $name,
            'iat' => DateTime::now()->getTimestamp()
        ];

        $this->assertEquals($expectedToken, $decodedToken);
    }
}
