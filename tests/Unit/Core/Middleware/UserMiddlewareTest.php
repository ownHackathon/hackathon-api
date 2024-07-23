<?php declare(strict_types=1);

namespace Test\Unit\Core\Middleware;

use App\Middleware\User\UserMiddleware;
use App\Service\User\UserService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Test\Data\TestConstants;
use Test\Unit\Mock\Service\MockUserService;

class UserMiddlewareTest extends AbstractMiddleware
{
    private UserService $userService;

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

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testReturnStatusNotFound(): void
    {
        $middleware = new UserMiddleware($this->userService);

        $response = $middleware->process(
            $this->request->withAttribute('userUuid', '-'),
            $this->handler
        );

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame($response->getStatusCode(), HTTP::STATUS_NOT_FOUND);
    }
}
