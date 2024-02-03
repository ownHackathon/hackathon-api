<?php declare(strict_types=1);

namespace App\Service;

use App\Service\System\TokenService;
use PHPUnit\Framework\TestCase;

class TokenServiceTest extends TestCase
{
    public function testCanGenerateToken(): void
    {
        $service = new TokenService();
        $token = $service->generateToken();

        self::assertIsString($token);
        self::assertSame(32, strlen($token));
    }
}
