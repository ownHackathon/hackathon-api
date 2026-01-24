<?php declare(strict_types=1);

namespace UnitTest\AppTest\Middleware;

use Exdrals\Identity\Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware;
use Laminas\Diactoros\Response\JsonResponse;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;
use UnitTest\Mock\Validator\MockAuthenticationValidator;
use UnitTest\Mock\Validator\MockAuthenticationValidatorFailed;

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
