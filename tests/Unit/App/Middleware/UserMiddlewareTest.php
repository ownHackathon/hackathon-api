<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware;

use App\Middleware\UserMiddleware;
use App\Service\UserService;
use Test\Unit\App\Mock\Service\MockUserService;
use Test\Unit\App\Mock\TestConstants;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;

class UserMiddlewareTest extends AbstractMiddleware
{
    private readonly UserService $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = new MockUserService();
    }

    public function testReturnResponseInterface(): void
    {
        $middleware = new UserMiddleware($this->userService);

        $response = $middleware->process(
            $this->request->withAttribute('userUuid', TestConstants::USER_UUID),
            $this->handler
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testReturnStatusNotFound(): void
    {
        $middleware = new UserMiddleware($this->userService);

        $response = $middleware->process(
            $this->request->withAttribute('userUuid', '-'),
            $this->handler
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame($response->getStatusCode(), HTTP::STATUS_NOT_FOUND);
    }
}
