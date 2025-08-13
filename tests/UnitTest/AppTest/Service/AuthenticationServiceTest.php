<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Service;

use PHPUnit\Framework\TestCase;
use ownHackathon\App\Service\Authentication\AuthenticationService;
use ownHackathon\UnitTest\Mock\Constants\Account;

class AuthenticationServiceTest extends TestCase
{
    private AuthenticationService $service;

    protected function setUp(): void
    {
        $this->service = new AuthenticationService();
    }

    public function testPasswordComparisonIsSuccessful(): void
    {
        $compare = $this->service->isPasswordMatch(Account::PASSWORD_STRING, Account::PASSWORD);

        $this->assertTrue($compare);
    }

    public function testPasswordComparisonFails(): void
    {
        $compare = $this->service->isPasswordMatch(Account::PASSWORD_STRING, Account::PASSWORD_INVALID);

        $this->assertFalse($compare);
    }
}
