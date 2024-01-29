<?php

namespace App\Mailer;

use Symfony\Component\Mime\Email;

interface MailerInterface
{
    public function send(Email $email): void;
}