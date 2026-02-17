<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Token;

use Exdrals\Core\Mailing\Infrastructure\EmailService;
use Exdrals\Identity\Domain\AccountActivationInterface;

use function sprintf;

readonly class ActivationTokenService
{
    public function __construct(
        private EmailService $emailService,
        private string $projectUri,
    ) {
    }

    public function sendEmail(AccountActivationInterface $activation): void
    {
        $text = sprintf(
            'Hallo!
                    Vielen Dank für Ihr Interesse an ownHackathon.

                    Sie haben eine Registrierung mit dieser E-Mail-Adresse angefordert.
                    Um den Vorgang abzuschließen und Ihr Benutzerkonto zu erstellen,
                    bestätigen Sie bitte Ihre E-Mail-Adresse über den folgenden Link:

                    %s/app/account/activation/%s

                    Hinweis: Erst wenn Sie diesen Link anklicken, wird Ihr Account verbindlich angelegt. Der Link ist zeitlich begrenzt gültig.

                    Falls Sie diese Anfrage nicht selbst gestellt haben, können Sie diese Nachricht einfach ignorieren.
                    Es wird in diesem Fall kein Konto erstellt und Ihre E-Mail-Adresse wird nicht bei uns gespeichert.

                    Mit freundlichen Grüßen,
                    Ihr Team von ownHackathon',
            $this->projectUri,
            $activation->token->toString()
        );

        $html = sprintf(
            '<p>Hallo!</p>
                    <p>Vielen Dank für Ihr Interesse an ownHackathon.</p>
                    <p>Sie haben eine Registrierung mit dieser E-Mail-Adresse angefordert.
                    Um den Vorgang abzuschließen und Ihr Benutzerkonto zu erstellen,
                    bestätigen Sie bitte Ihre E-Mail-Adresse über den folgenden Link:</p>
                    <p><a href=\'%s/app/account/activation/%s\'>Account erstellen</a></p>
                    <p><strong>Hinweis:</strong> Erst wenn Sie diesen Link anklicken, wird Ihr Account verbindlich angelegt.
                    Der Link ist nur für begrenzte Zeit gültig.</p>
                    <p>Falls Sie diese Anfrage nicht selbst gestellt haben, können Sie diese Nachricht einfach ignorieren.
                    Es wird in diesem Fall kein Konto erstellt und Ihre E-Mail-Adresse wird nicht dauerhaft bei uns gespeichert.</p>
                    <p>Mit freundlichen Grüßen,<br>
                    Ihr Team von ownHackathon</p>',
            $this->projectUri,
            $activation->token->toString()
        );

        $this->emailService->send($activation->email, $text, $html, 'Account Aktivierung');
    }
}
