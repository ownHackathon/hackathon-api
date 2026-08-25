<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Account;

use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Domain\Exception\HttpHandledInvalidArgumentAsSuccessException;
use Monolog\Level;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;

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
