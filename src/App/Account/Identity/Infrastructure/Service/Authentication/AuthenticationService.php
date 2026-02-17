<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Authentication;

readonly class AuthenticationService
{
    public function isPasswordMatch(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
