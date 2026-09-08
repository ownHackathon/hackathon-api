<?php declare(strict_types=1);

namespace App\Mailing\Application;

use App\Mailing\Api\EmailSendInterface;
use App\Mailing\Api\EmailType;
use App\Mailing\Infrastructure\Service\EmailService;

readonly final class EmailSendService implements EmailSendInterface
{
    public function __construct(
        private EmailService $emailService,
    ) {
    }

    public function send(EmailType $email, string $plainText, string $html, string $subject): void
    {
        $this->emailService->send($email, $plainText, $html, $subject);
    }
}
