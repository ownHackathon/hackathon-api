<?php declare(strict_types=1);

namespace ownHackathon\App\Mailing\Application\Port;

use ownHackathon\App\Mailing\Domain\EmailType;

interface MailerInterface
{
    public function send(EmailType $email, string $plainText, string $html, string $subject): void;
}
