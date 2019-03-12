<?php

namespace WalletAccountant\Common\Authenticator;

use InvalidArgumentException as StandardInvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\User;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;

/**
 * LoginAuthenticator
 */
final class LoginAuthenticator implements AuthenticatorInterface
{
    private const AUTHENTICATION_POST_EMAIL = 'email';
    private const AUTHENTICATION_POST_PASSWORD = 'password';
    private const JWT_EXPIRE_DAYS = 10;

    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * @var UserProjectionRepositoryInterface
     */
    private $userProjectionRepository;

    /**
     * @var PasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @param JWTEncoderInterface               $jwtEncoder
     * @param PasswordEncoder                   $passwordEncoder
     * @param UserProjectionRepositoryInterface $userProjectionRepository
     */
    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        PasswordEncoder $passwordEncoder,
        UserProjectionRepositoryInterface $userProjectionRepository
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->passwordEncoder = $passwordEncoder;
        $this->userProjectionRepository = $userProjectionRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        return [
            self::AUTHENTICATION_POST_EMAIL => $request->request->get(self::AUTHENTICATION_POST_EMAIL),
            self::AUTHENTICATION_POST_PASSWORD => $request->request->get(self::AUTHENTICATION_POST_PASSWORD)
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return UserInterface
     *
     * @throws InvalidArgumentException
     * @throws CustomUserMessageAuthenticationException
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $user = $this->userProjectionRepository->getByEmailOrNull(
            Email::createFromString($credentials[self::AUTHENTICATION_POST_EMAIL])
        );

        if (!$user instanceof UserInterface) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('invalid user with email "%s"', $credentials[self::AUTHENTICATION_POST_EMAIL])
            );
        }

        if (!$user->getStatus()->canLogin()) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('User email "%s" cannot login (%s)', $user->getEmail(), $user->getStatus()->toString())
            );
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $encodedPassword = $this->passwordEncoder->encodeUserPassword(
            $user,
            $credentials[self::AUTHENTICATION_POST_PASSWORD]
        );

        return $user->getPassword() === $encodedPassword;
    }

    /**
     * {@inheritdoc}
     *
     * @throws StandardInvalidArgumentException
     */
    public function createAuthenticatedToken(UserInterface $user, $providerKey): PostAuthenticationGuardToken
    {
        return new PostAuthenticationGuardToken($user, $providerKey, $user->getRoles());
    }

    /**
     * {@inheritdoc}
     *
     * @throws JWTEncodeFailureException
     * @throws InvalidArgumentException
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): JsonResponse
    {
        /** @var User $user */
        $user = $token->getUser();

        //Generate JWT token
        $jWToken = $this->jwtEncoder->encode(
            [
                'sub' => $user->getId()->toString(),
                'iat' => DateTime::now()->getTimestamp(),
                'exp' => DateTime::now()->addDays(self::JWT_EXPIRE_DAYS)->getTimestamp(),
                'email' => $user->getEmail()->toString(),
                'name' => [
                    'first' => $user->getName()->getFirst(),
                    'last' => $user->getName()->getLast()
                ]
            ]
        );

        return new JsonResponse(['token' => $jWToken]);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse(['error' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return $request->request->has(self::AUTHENTICATION_POST_EMAIL)
            && $request->request->get(self::AUTHENTICATION_POST_EMAIL) !== ''
            && $request->request->has(self::AUTHENTICATION_POST_PASSWORD)
            && $request->request->get(self::AUTHENTICATION_POST_PASSWORD) !== '';
    }
}
