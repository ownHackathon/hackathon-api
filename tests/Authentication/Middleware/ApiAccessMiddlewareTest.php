<?php declare(strict_types=1);

namespace Authentication\Test\Middleware;

use App\Test\Middleware\AbstractMiddlewareTest;
use Authentication\Middleware\ApiAccessMiddleware;
use Authentication\Test\Mock\Service\MockApiAccessService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;

class ApiAccessMiddlewareTest extends AbstractMiddlewareTest
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