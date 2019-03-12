<?php

namespace WalletAccountant\ProcessManager;

use Swift_Mailer;
use Swift_Message;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use WalletAccountant\Domain\User\Event\UserPasswordRecoveryInitiated;

/**
 * SendPasswordRecoveryInitiatedEmailProcessManager
 */
class SendPasswordRecoveryInitiatedEmailProcessManager
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Twig_Environment
     */
    private $templating;

    /**
     * @param Swift_Mailer     $mailer
     * @param Twig_Environment $templating
     */
    public function __construct(Swift_Mailer $mailer, Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param UserPasswordRecoveryInitiated $event
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function __invoke(UserPasswordRecoveryInitiated $event): void
    {
        $message = (new Swift_Message('Password recovery initiated'))
            ->setFrom('wlltccntnt@gmail.com')
            ->setTo($event->email())
            ->setBody(
                $this->templating->render(
                    'initiatePasswordRecovery.html.twig',
                    [/*'name' => $name*/]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
