<?php

namespace App\Service;

use DateTimeImmutable;
use App\Service\MailerService;
use App\Interface\UserNotifyEmailInterface;

class UserNotifyEmailService implements UserNotifyEmailInterface
{
    private MailerService $mailer;

    public function __construct(
        MailerService $mailer,
    ) {
        $this->mailer = $mailer;
    }

    public function sendVerificationApprovedEmail(string $to): void
    {
        $email = $this->mailer->prepareTemplate(
            $to,
            'Votre compte a été approuvé',
            'emails/verification_approved.html.twig',
            [
                'lien' => 'localhost:8000/producteur',
            ]
        );

        $this->mailer->send($email);
    }

    public function sendVerificationRefusedEmail(string $to): void
    {
        $email = $this->mailer->prepareTemplate(
            $to,
            'Votre compte a été refusée',
            'emails/verification_refused.html.twig',
            [
                'lien' => 'localhost:8000/',
            ]
        );

        $this->mailer->send($email);
    }
}