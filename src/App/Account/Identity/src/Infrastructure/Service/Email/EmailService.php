<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Service\Email;

use Exdrals\Account\Identity\Domain\Email as EmailType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private EmailType $senderEmail,
    ) {
    }

    public function send(EmailType $email, string $plainText, string $html, string $subject): void
    {
        $email = new Email()
            ->from($this->senderEmail->toString())
            ->to($email->toString())
            ->subject($subject)
            ->text($plainText)
            ->html($html);

        $this->mailer->send($email);
    }
}
