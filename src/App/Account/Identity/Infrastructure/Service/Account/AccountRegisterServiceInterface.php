<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Account;

use ownHackathon\Core\Mailing\Domain\EmailType;

interface AccountRegisterServiceInterface
{
    public function register(EmailType $email): void;
}
