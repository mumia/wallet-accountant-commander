<?php

namespace WalletAccountant\Common\Authenticator;

use InvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
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
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;

/**
 * JwtAuthenticator
 */
final class JwtAuthenticator implements AuthenticatorInterface
{
    private const AUTHORIZATION_HEADER_KEY = 'Authorization';
    private const AUTHORIZATION_TOKEN_PREFIX = 'Bearer';

    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * @var UserProjectionRepositoryInterface
     */
    private $userProjectionRepository;

    /**
     * @param JWTEncoderInterface               $jwtEncoder
     * @param UserProjectionRepositoryInterface $userProjectionRepository
     */
    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        UserProjectionRepositoryInterface $userProjectionRepository
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->userProjectionRepository = $userProjectionRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            self::AUTHORIZATION_TOKEN_PREFIX,
            self::AUTHORIZATION_HEADER_KEY
        );
        $token = $extractor->extract($request);

        if ($token === false) {
            return null;
        }

        return ['token' => $token];
    }

    /**
     * {@inheritdoc}
     *
     * @return UserInterface
     *
     * @throws JWTDecodeFailureException
     * @throws CustomUserMessageAuthenticationException
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $data = $this->jwtEncoder->decode($credentials['token']);
        if ($data === false) {
            throw new CustomUserMessageAuthenticationException('Invalid jwt token');
        }

        $user = $this->userProjectionRepository->getByEmailOrNull($data['email']);

        if (!$user instanceof UserInterface) {
            throw new CustomUserMessageAuthenticationException('Invalid user account');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // This validation is done by decoding the token
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function createAuthenticatedToken(UserInterface $user, $providerKey): PostAuthenticationGuardToken
    {
        return new PostAuthenticationGuardToken($user, $providerKey, $user->getRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
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
        return new JsonResponse(['error' => 'Authentication header required'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has(self::AUTHORIZATION_HEADER_KEY);
    }
}
