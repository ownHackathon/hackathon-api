<?php declare(strict_types=1);

namespace ownHackathon\App\Mailing\Infrastructure\Service;

use ownHackathon\App\Mailing\Application\Port\MailerInterface;
use ownHackathon\App\Mailing\Domain\EmailType;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;

readonly final class EmailService implements MailerInterface
{
    public function __construct(
        private SymfonyMailerInterface $mailer,
        private EmailType $senderEmail,
    ) {
    }

    #[\Override]
    public function send(EmailType $email, string $plainText, string $html, string $subject): void
    {
        $message = new Email()
            ->from($this->senderEmail->toString())
            ->to($email->toString())
            ->subject($subject)
            ->text($plainText)
            ->html($html);

        $this->mailer->send($message);
    }
}
