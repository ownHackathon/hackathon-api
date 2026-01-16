<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use ownHackathon\App\Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware;
use ownHackathon\FunctionalTest\Mock\NullLogger;
use ownHackathon\UnitTest\Mock\Validator\MockAuthenticationValidator;
use ownHackathon\UnitTest\Mock\Validator\MockAuthenticationValidatorFailed;

class AuthenticationValidationMiddlewareTest extends AbstractTestMiddleware
{
    public function testValidationIsValid(): void
    {
        $middleware = new AuthenticationValidationMiddleware(
            new MockAuthenticationValidator(),
            new NullLogger(),
        );

        $response = $middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testValidationFailed(): void
    {
        $middleware = new AuthenticationValidationMiddleware(
            new MockAuthenticationValidatorFailed(),
            new NullLogger(),
        );

        $response = $middleware->process($this->request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', '400');
        $this->assertJsonValueEquals($json, '$.message', 'Invalid Data');
    }
}
