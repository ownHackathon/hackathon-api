<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\UnitTest\Mock\Validator\MockAuthenticationValidator;
use ownHackathon\UnitTest\Mock\Validator\MockAuthenticationValidatorFailed;

class AuthenticationValidationMiddlewareTest extends AbstractTestMiddleware
{
    public function testValidationIsValid(): void
    {
        $middleware = new AuthenticationValidationMiddleware(
            new MockAuthenticationValidator(),
        );

        $response = $middleware->process($this->request, $this->handler);

        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testValidationFailed(): void
    {
        $middleware = new AuthenticationValidationMiddleware(
            new MockAuthenticationValidatorFailed(),
        );

        $this->expectException(HttpUnauthorizedException::class);
        $middleware->process($this->request, $this->handler);
    }
}
