<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared\Domain\Mailing;

use ownHackathon\Core\Mailing\Domain\EmailType;

interface MailerInterface
{
    public function send(EmailType $email, string $plainText, string $html, string $subject): void;
}
