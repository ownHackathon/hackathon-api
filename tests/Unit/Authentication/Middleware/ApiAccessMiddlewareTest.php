<?php declare(strict_types=1);

namespace Test\Unit\Authentication\Middleware;

use App\Middleware\Authentication\ApiAccessMiddleware;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Service\MockApiAccessService;

class ApiAccessMiddlewareTest extends AbstractMiddleware
{
    private MockApiAccessService $apiAccessService;

    public function setUp(): void
    {
        parent::setUp();
        $this->apiAccessService = new MockApiAccessService();
    }

    public function testReturnResponseInterfaceWithoutPort(): void
    {
        $middleware = new ApiAccessMiddleware($this->apiAccessService);

        $response = $middleware->process(
            $this->request->withHeader('Host', 'localhost'),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testReturnResponseInterfaceWithPort(): void
    {
        $middleware = new ApiAccessMiddleware($this->apiAccessService);

        $response = $middleware->process(
            $this->request->withHeader('Host', 'localhost:80'),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testReturnJSonResponse(): void
    {
        $middleware = new ApiAccessMiddleware($this->apiAccessService);

        $response = $middleware->process(
            $this->request->withHeader('Host', 'example.com'),
            $this->handler
        );

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
