<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPassword
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $templating;
    /**
     * @var TokenGeneratorInterface
     */
    private $generator;

    /**
     * ResetPassword constructor.
     * @param EntityManagerInterface  $em
     * @param \Swift_Mailer           $mailer
     * @param \Twig_Environment       $templating
     * @param TokenGeneratorInterface $generator
     */
    public function __construct(
        EntityManagerInterface $em,
        \Swift_Mailer $mailer,
        \Twig_Environment $templating,
        TokenGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->generator = $generator;
    }

    /**
     * @param $user
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function reset($user)
    {
        $this->addToken($user);
        $this->sendResetPasswordEmail($user);
    }

    /**
     * Generate and add a temporary token to the target user
     * @param $user
     */
    public function addToken($user)
    {
        $token = $this->generateToken();
        $user->setResetPasswordToken($token);
        $this->em->flush();
    }

    /**
     * @param $user
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendResetPasswordEmail($user)
    {
        $message = (new \Swift_Message('Demande reinitialisation de mot de passe'))
            ->setFrom('no-remply@symfony-blog.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('blog/security/password/email/_password_reset_email.html.twig', [
                    'username' => $user->getUsername()
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

    /**
     * @return string
     */
    private function generateToken()
    {
        return $this->generator->generateToken();
    }
}
