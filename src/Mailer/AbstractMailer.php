<?php

namespace App\Mailer;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface as Mailer;
use Twig\Environment;

class AbstractMailer implements MailerInterface
{
    public function __construct(
        private readonly Mailer $mailer,
        private readonly Environment $environment
    )
    {

    }

    public function generateTemplate(string $template, array $context = []): string
    {
        return $this->environment->load($template);
    }

    public function send(Email $email): void
    {
        $this->mailer->send($email);
    }
}