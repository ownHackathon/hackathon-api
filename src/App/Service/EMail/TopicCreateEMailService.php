<?php declare(strict_types=1);

namespace App\Service\EMail;

use App\Entity\Topic;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

readonly class TopicCreateEMailService implements EMailServiceInterface
{
    public function __construct(
        private Mailer $mailer,
        private string $mailSender,
        private string $projectUri,
    ) {
    }

    public function send(Topic $topic): void
    {
        $subject = sprintf('A new topic was submitted: %s', $topic->getTopic());
        $text = sprintf(
            "Check the new topic and approve it if necessary\r\n\r\nTitle:\r\n%s\r\n\r\nDescription:\r\n%s\r\n\r\nLink:\r\n%s/topic/%s",
            $topic->getTopic(),
            $topic->getDescription(),
            $this->projectUri,
            $topic->getUuid(),
        );
        $email = (new Email())
            ->from($this->mailSender)
            ->to('hackathon@exdrals.de')
            ->subject($subject)
            ->text($text);

        $this->mailer->send($email);
    }
}
