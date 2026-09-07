<?php declare(strict_types=1);

namespace App\Account\Identity\Api;

use App\Mailing\Api\EmailType;

interface AccountRegisterServiceInterface
{
    public function register(EmailType $email): void;
}
