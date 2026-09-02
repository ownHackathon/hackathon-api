<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Account;

use ownHackathon\App\Mailing\Domain\EmailType;

readonly final class PasswordService
{
    public function __construct(
        private AccountService $accountService,
    ) {
    }

    public function sendTokenForInitiateResetPassword(EmailType $email): void
    {
        if ($this->accountService->isEmailAvailable($email)) {
            return;
        }

        $this->accountService->sendTokenForPasswordChange($email);
    }
}
