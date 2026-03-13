<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class MailerService
{
    private MailerInterface $mailer;
    private string $fromEmail;
    private string $fromName;

    public function __construct(
        MailerInterface $mailer,
        string $fromEmail,
        string $fromName
    ) {
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * Prépare un Email (sans logique métier)
     */
    public function prepareTemplate(
        string $to,
        string $subject,
        string $template,
        array $context = []
    ): TemplatedEmail {
        return (new TemplatedEmail())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);
    }

    /**
     * Envoi réel
     */
    public function send(Email $email): void
    {
        $this->mailer->send($email);
    }
}