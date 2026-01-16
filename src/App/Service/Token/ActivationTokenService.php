<?php declare(strict_types=1);

namespace ownHackathon\App\Service\Token;

use ownHackathon\Core\Entity\Account\AccountActivationInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use function sprintf;

readonly class ActivationTokenService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    public function sendEmail(AccountActivationInterface $activation): void
    {
        $text = sprintf('Your token to activate your Account: %s', $activation->token->getHex()->toString());

        $email = new Email()
            ->from('no-reply@stormannsgal.de')
            ->to($activation->email->toString())
            ->subject('Account Activation Code')
            ->text($text);

        $this->mailer->send($email);
    }
}
