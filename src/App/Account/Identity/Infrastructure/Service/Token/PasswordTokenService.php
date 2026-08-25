<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Token;

use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Mailing\Infrastructure\EmailService;
use Exdrals\Core\Shared\Domain\Token\TokenInterface;

use function sprintf;

readonly class PasswordTokenService
{
    public function __construct(
        private EmailService $emailService,
        private string $projectUri,
    ) {
    }

    public function sendEmail(EmailType $email, TokenInterface $token): void
    {
        $text = sprintf(
            'Hallo!

                    Vielen Dank für Ihr Interesse an unserem Service.

                    Sie haben versucht, sich mit dieser E-Mail-Adresse neu zu registrieren oder wollen Ihr Passwort zurücksetzen. 
                    Da unter dieser Adresse bereits ein aktives Konto bei uns besteht, 
                    senden wir Ihnen hiermit einen Link, mit dem Sie Ihr Passwort zurücksetzen können, 
                    falls Sie den Zugriff auf Ihren Account wiederherstellen möchten.

                    Klicken Sie auf den folgenden Link, um ein neues Passwort festzulegen:
                    %s/app/account/password/%s

                    Falls Sie keine Registrierung oder Passwortänderung veranlasst haben, 
                    können Sie diese Nachricht einfach ignorieren. 
                    Ihr Account bleibt weiterhin mit Ihrem bestehenden Passwort geschützt.

                    Mit freundlichen Grüßen,
                    Ihr Team von ownHackathon',
            $this->projectUri,
            $token->token->toString()
        );

        $html = sprintf(
            '<p>Hallo!</p>
                    <p>Vielen Dank für Ihr Interesse an unserem Service.</p>
                    <p>Sie haben versucht, sich mit dieser E-Mail-Adresse neu zu registrieren oder wollen Ihr Passwort zurücksetzen. 
                    Da unter dieser Adresse bereits ein aktives Konto bei uns besteht, 
                    senden wir Ihnen hiermit einen Link, mit dem Sie Ihr Passwort zurücksetzen können, 
                    falls Sie den Zugriff auf Ihren Account wiederherstellen möchten.</p>
                    <p>Klicken Sie auf den folgenden Link, um ein neues Passwort festzulegen:<br>
                    <a href=\'%s/app/account/password/%s\'>Passwort resetten</a></p>
                    <p>Falls Sie keine Registrierung oder Passwortänderung veranlasst haben, 
                    können Sie diese Nachricht einfach ignorieren. Ihr Account bleibt weiterhin mit 
                    Ihrem bestehenden Passwort geschützt.</p>
                    <p>Mit freundlichen Grüßen,<br>
                    Ihr Team von ownHackathon</p>',
            $this->projectUri,
            $token->token->toString()
        );

        $this->emailService->send($email, $text, $html, 'Passwort zurücksetzen Link');
    }
}
