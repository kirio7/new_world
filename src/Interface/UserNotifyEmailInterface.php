<?php

namespace App\Interface;

Interface UserNotifyEmailInterface
{
    public function sendVerificationApprovedEmail(string $to): void;

    public function sendVerificationRefusedEmail(string $to): void;
}