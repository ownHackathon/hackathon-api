<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\Core\Message\ResponseMessage;
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

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', StatusCodeInterface::STATUS_UNAUTHORIZED);
        $this->assertJsonValueEquals($json, '$.message', ResponseMessage::DATA_INVALID);
    }
}
