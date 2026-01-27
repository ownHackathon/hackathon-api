<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Mailing;

use Exdrals\Mailing\Domain\EmailType;

interface MailerInterface
{
    public function send(EmailType $email, string $plainText, string $html, string $subject): void;
}
