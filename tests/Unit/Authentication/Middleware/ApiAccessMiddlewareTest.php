<?php declare(strict_types=1);

namespace Test\Unit\Authentication\Middleware;

use Test\Unit\App\Middleware\AbstractMiddleware;
use Authentication\Middleware\ApiAccessMiddleware;
use Test\Unit\Authentication\Mock\Service\MockApiAccessService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;

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

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testReturnResponseInterfaceWithPort(): void
    {
        $middleware = new ApiAccessMiddleware($this->apiAccessService);

        $response = $middleware->process(
            $this->request->withHeader('Host', 'localhost:80'),
            $this->handler
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testReturnJSonResponse(): void
    {
        $middleware = new ApiAccessMiddleware($this->apiAccessService);

        $response = $middleware->process(
            $this->request->withHeader('Host', 'example.com'),
            $this->handler
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
