<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Authentication;

readonly final class AuthenticationService
{
    public function isPasswordMatch(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
