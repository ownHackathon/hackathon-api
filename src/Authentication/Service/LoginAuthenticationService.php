<?php
declare(strict_types=1);

namespace Authentication\Service;

use App\Model\User;

class LoginAuthenticationService
{
    public function isUserDataCorrect(?User $user, string $password): bool
    {
        if (!isset($user)) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        return true;
    }
}
