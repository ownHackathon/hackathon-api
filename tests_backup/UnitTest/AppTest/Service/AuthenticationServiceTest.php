<?php declare(strict_types=1);

namespace UnitTest\AppTest\Service;

use Exdrals\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use PHPUnit\Framework\TestCase;
use UnitTest\Mock\Constants\Account;

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
