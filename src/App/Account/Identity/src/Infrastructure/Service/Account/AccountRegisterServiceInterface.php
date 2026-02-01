<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Account;

use Exdrals\Mailing\Domain\EmailType;

interface AccountRegisterServiceInterface
{
    public function register(EmailType $email): void;
}
