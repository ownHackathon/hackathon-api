<?php declare(strict_types=1);

namespace App\Service\Authentication;

use App\Entity\User;

use function password_verify;

readonly class LoginAuthenticationService
{
    public function isUserDataCorrect(?User $user, string $password): bool
    {
        if (!($user instanceof User)) {
            return false;
        }

        return password_verify($password, $user->password);
    }
}
