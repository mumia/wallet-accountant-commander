<?php

namespace WalletAccountant\Domain\User\Handler;

use Respect\Validation\Validator;
use function sprintf;
use WalletAccountant\Common\Authenticator\PasswordEncoder;
use WalletAccountant\Common\Exceptions\User\LogicException;
use WalletAccountant\Common\Exceptions\User\UserAggregateNotFoundException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\User\Command\RecoverUserPassword;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;
use WalletAccountant\Domain\User\UserRepositoryInterface;

/**
 * RecoverUserPasswordHandler
 */
final class RecoverUserPasswordHandler
{
    private const PASSWORD_MIN_LENGTH = 6;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserProjectionRepositoryInterface
     */
    private $userProjectionRepository;

    /**
     * @var PasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @param UserRepositoryInterface           $userRepository
     * @param UserProjectionRepositoryInterface $userProjectionRepository
     * @param PasswordEncoder                   $passwordEncoder
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserProjectionRepositoryInterface $userProjectionRepository,
        PasswordEncoder $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->userProjectionRepository = $userProjectionRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param RecoverUserPassword $command
     *
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     * @throws UserAggregateNotFoundException
     * @throws LogicException
     */
    public function __invoke(RecoverUserPassword $command): void
    {
        $code = $command->code();
        $password = $command->password();
        $repeatPassword = $command->repeatPassword();

        $this->validateInputs($code, $password, $repeatPassword);

        $user = $this->userProjectionRepository->getByPasswordRecoveryCode($code);
        $id = $user->getAggregateId();
        $userDomain = $this->userRepository->get(UserId::createFromString($id));

        if (!$userDomain->hasRecovery()) {
            throw new LogicException('user is not in password recovery mode');
        }

        if (!$userDomain->recovery()->validateRecovery($code)) {
            throw new LogicException(sprintf('user password recovery code "%s" does not match', $code));
        }

        $encodedPassword = $this->passwordEncoder->encodeUserPassword($user, $password);
        $userDomain->recoverPassword($encodedPassword);

        $this->userRepository->save($userDomain);
    }

    /**
     * @param string $code
     * @param string $password
     * @param string $repeatPassword
     *
     * @throws InvalidArgumentException
     */
    private function validateInputs(string $code, string $password, string $repeatPassword): void
    {
        if (!Validator::stringType()->notEmpty()->length(16, 16, true)->validate($code)) {
            throw new InvalidArgumentException(sprintf('unable to recover user password, invalid code "%s"', $code));
        }

        if (!Validator::stringType()->notEmpty()->validate($password)) {
            throw new InvalidArgumentException('unable to recover user password, invalid password');
        }

        if (!Validator::length(self::PASSWORD_MIN_LENGTH)->validate($password)) {
            throw new InvalidArgumentException(
                sprintf(
                    'unable to recover user password, password too short, must be at least %d chars long',
                    self::PASSWORD_MIN_LENGTH
                )
            );
        }

        if (!Validator::stringType()->notEmpty()->validate($repeatPassword)) {
            throw new InvalidArgumentException('unable to recover user password, invalid repeat password');
        }


        if (!Validator::length(self::PASSWORD_MIN_LENGTH)->validate($repeatPassword)) {
            throw new InvalidArgumentException(
                sprintf(
                    'unable to recover user password, repeat password too short, must be at least %d chars long',
                    self::PASSWORD_MIN_LENGTH
                )
            );
        }

        if ($password !== $repeatPassword) {
            throw new InvalidArgumentException('unable to recover user password, password mismatch');
        }
    }
}
