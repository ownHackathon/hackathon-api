<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Domain\Mailing;

use Exdrals\Core\Mailing\Domain\EmailType;

interface MailerInterface
{
    public function send(EmailType $email, string $plainText, string $html, string $subject): void;
}
