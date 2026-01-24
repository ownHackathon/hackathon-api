<?php declare(strict_types=1);

namespace Exdrals\Mailing\Domain;

interface MailerInterface
{
    public function send(EmailType $email, string $plainText, string $html, string $subject): void;
}
