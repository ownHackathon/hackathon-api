<?php declare(strict_types=1);

namespace App\Mailing\Api;

interface MailerInterface
{
    public function send(EmailType $email, string $plainText, string $html, string $subject): void;
}
