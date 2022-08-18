<?php declare(strict_types=1);

namespace Authentication\Service;

use App\Model\User;

use function password_verify;

class LoginAuthenticationService
{
    public function isUserDataCorrect(?User $user, string $password): bool
    {
        if (null === $user) {
            return false;
        }

        return password_verify($password, $user->getPassword());
    }
}
