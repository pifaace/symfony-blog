<?php

namespace App\Services;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function buildAndSendMail(string $subject, $recever, $body)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom('no-remply@symfony-blog.com')
            ->setTo($recever)
            ->setBody($body, 'text/html');

        $this->send($message);
    }

    private function send($message)
    {
        $this->mailer->send($message);
    }
}
