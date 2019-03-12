<?php

namespace WalletAccountant\Common\Authenticator;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use WalletAccountant\Document\User;

/**
 * PasswordEncoder
 */
class PasswordEncoder
{
    private const PASSWORD_SALT_LAYOUT = '|_%s_.__%s|';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param UserInterface $user
     * @param string        $password
     *
     * @return string
     */
    public function encodeUserPassword(UserInterface $user, string $password): string
    {
        $saltedPassword = sprintf(self::PASSWORD_SALT_LAYOUT, $password, $user->getSalt());

        return $this->passwordEncoder->encodePassword($user, $saltedPassword);
    }
}
