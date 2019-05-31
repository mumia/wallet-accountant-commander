<?php

namespace WalletAccountant\Tests\Functional\Authenticator;

use Doctrine\DBAL\DBALException;
use stdClass;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Tests\Functional\Fixtures\User\UserWithPassword;
use WalletAccountant\Tests\Functional\FunctionalTestCase;

/**
 * LoginAuthenticatorTest
 */
class LoginAuthenticatorTest extends FunctionalTestCase
{
    /**
     * @throws DBALException
     */
    public function testLogin()
    {
        DateTime::setTestNow(DateTime::now());

        $this->loadFixtures();

        $response = $this->login(UserWithPassword::EMAIL, UserWithPassword::PASSWORD);

        $token = json_decode($response->getContent(), true);

        $this->assertFalse(isset($token['error']));

        $encoder = self::$container->get('test.jwt.encoder');
        $decodedToken = $encoder->decode($token['token']);

        // This mimics the behaviour of the 'lcobucci' decoder, but in my opinion it should return the array.
        $name = new stdClass();
        $name->first = UserWithPassword::FIRST_NAME;
        $name->last = UserWithPassword::LAST_NAME;

        $expectedToken = [
            'exp' => DateTime::now()->addDays(10)->getTimestamp(),
            'email' => UserWithPassword::EMAIL,
            'name' => $name,
            'iat' => DateTime::now()->getTimestamp(),
            'sub' => UserWithPassword::EVENT_AGGREGATE_ID
        ];

        $this->assertEquals($expectedToken, $decodedToken);
    }
}
