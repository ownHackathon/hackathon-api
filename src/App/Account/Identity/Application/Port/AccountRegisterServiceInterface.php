<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Application\Port;

use ownHackathon\App\Mailing\Domain\EmailType;

interface AccountRegisterServiceInterface
{
    public function register(EmailType $email): void;
}
