<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Service;

use ownHackathon\App\Service\Authentication\AuthenticationService;
use ownHackathon\UnitTest\Mock\Constants\Account;

readonly class MockAuthenticationService extends AuthenticationService
{
    public function isPasswordMatch(string $password, string $hash): bool
    {
        return $password === Account::PASSWORD;
    }
}
