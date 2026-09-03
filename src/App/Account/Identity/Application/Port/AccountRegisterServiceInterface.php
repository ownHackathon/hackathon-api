<?php declare(strict_types=1);

namespace App\Account\Identity\Application\Port;

use App\Mailing\Domain\EmailType;

interface AccountRegisterServiceInterface
{
    public function register(EmailType $email): void;
}
