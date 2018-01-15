<?php

namespace WalletAccountant\Tests\Functional\Fixtures\Authenticator;

use Doctrine\DBAL\Connection as DBALConnection;
use Doctrine\DBAL\DBALException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use WalletAccountant\Common\Authenticator\PasswordEncoder;
use WalletAccountant\Document\User;
use WalletAccountant\Domain\User\User as UserDomain;
use WalletAccountant\Domain\User\Email\Email as EmailDomain;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name as NameDomain;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;
use WalletAccountant\Projection\ProjectionRunner;
use WalletAccountant\Tests\Functional\Fixtures\AbstractFixtures;

/**
 * AuthenticatorFixtures
 */
class AuthenticatorFixtures extends AbstractFixtures
{
    public const ID = 'c2660624-aa84-41f6-885f-4edc35777dd8';
    public const EMAIL = 'fakeemail@faketestdomain.tld';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const PASSWORD = 'password';

    /**
     * @var UserProjectionRepositoryInterface
     */
    private $userProjectionRepository;

    /**
     * @var PasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * @param AggregateRepository               $aggregateRepository
     * @param ProjectionRunner                  $projectionRunner
     * @param DBALConnection                    $dbalConnection
     * @param UserProjectionRepositoryInterface $userProjectionRepository
     * @param PasswordEncoder                   $passwordEncoder
     * @param JWTEncoderInterface               $jwtEncoder
     */
    public function __construct(
        AggregateRepository $aggregateRepository,
        ProjectionRunner $projectionRunner,
        DBALConnection $dbalConnection,
        UserProjectionRepositoryInterface $userProjectionRepository,
        PasswordEncoder $passwordEncoder,
        JWTEncoderInterface $jwtEncoder
    ) {
        parent::__construct($aggregateRepository, $projectionRunner, $dbalConnection);

        $this->userProjectionRepository = $userProjectionRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
    }

    /**
     * @throws DBALException
     */
    public function userWithPassword(): void
    {
        $this->resetDatabase();

        $userDomain = UserDomain::createUser(
            UserId::createFromString(self::ID),
            EmailDomain::createFromString(self::EMAIL),
            new NameDomain(self::FIRST_NAME, self::LAST_NAME)
        );

        $userDomain->initiatePasswordRecovery('myrecoverycode');

        $this->runEvents($userDomain);

        $user = $this->userProjectionRepository->getByAggregateId($userDomain->id());

        $userDomain->recoverPassword($this->getEncodedPassword($user));

        $this->runEvents($userDomain);
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getEncodedPassword(User $user): string
    {
        return $this->passwordEncoder->encodeUserPassword($user, self::PASSWORD);
    }

    /**
     * @param string $token
     *
     * @return array
     *
     * @throws JWTDecodeFailureException
     */
    public function decodeJWToken(string $token): array
    {
        return $this->jwtEncoder->decode($token);
    }
}
