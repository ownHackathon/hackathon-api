<?php declare(strict_types=1);

namespace App\Mailing\Infrastructure\Service;

use App\Mailing\Api\EmailType;

interface MailerInterface
{
    public function send(EmailType $email, string $plainText, string $html, string $subject): void;
}
