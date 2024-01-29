<?php

namespace App\Mailer;

use App\Entity\User;
use Symfony\Component\Mime\Email;

class ForgotPasswordMailer extends AbstractMailer
{
    public function sendEmail(User $user): void
    {
        $email = new Email();
        $email->setBody($this->generateTemplate(''))
    }
}