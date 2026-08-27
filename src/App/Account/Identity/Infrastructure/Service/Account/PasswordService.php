<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Account;

use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\Shared\Domain\Exception\HttpHandledInvalidArgumentAsSuccessException;
use Monolog\Level;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;

readonly class PasswordService
{
    public function __construct(
        private AccountService $accountService,
    ) {
    }

    public function sendTokenForInitiateResetPassword(EmailType $email): void
    {
        if ($this->accountService->isEmailAvailable($email)) {
            throw new HttpHandledInvalidArgumentAsSuccessException(
                IdentityLogMessage::PASSWORD_REQUEST_MISSING_ACCOUNT,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'email:' => $email->toString(),
                ],
                Level::Warning
            );
        }

        $this->accountService->sendTokenForPasswordChange($email);
    }
}
