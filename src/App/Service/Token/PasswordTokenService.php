<?php declare(strict_types=1);

namespace ownHackathon\App\Service\Token;

use ownHackathon\Core\Entity\Token\TokenInterface;
use ownHackathon\Core\Type\Email as EmailType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use function sprintf;

readonly class PasswordTokenService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    public function sendEmail(EmailType $email, TokenInterface $token): void
    {
        $text = sprintf('Your token to reset your password: %s', $token->token->getHex()->toString());

        $email = (new Email())
            ->from('no-reply@stormannsgal.de')
            ->to($email->toString())
            ->subject('Password Forgotten Code')
            ->text($text);

        $this->mailer->send($email);
    }
}
