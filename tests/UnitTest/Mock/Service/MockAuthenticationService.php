<?php declare(strict_types=1);

namespace UnitTest\Mock\Service;

use App\Service\Authentication\AuthenticationService;
use UnitTest\Mock\Constants\Account;

readonly class MockAuthenticationService extends AuthenticationService
{
    public function isPasswordMatch(string $password, string $hash): bool
    {
        return $password === Account::PASSWORD;
    }
}
