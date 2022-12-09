<?php declare(strict_types=1);

namespace App\Test\Middleware;

use App\Middleware\FrontLoaderMiddleware;
use App\Test\Mock\Handler\MockIndexHandler;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FrontLoaderMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testReturnResponseInterface(): void
    {
        $middleware = new FrontLoaderMiddleware(new MockIndexHandler($this->request));

        $response = $middleware->process(
            $this->request->withHeader('x-frontloader', 'x-frontloader'),
            $this->handler
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testReturnIndexHandler(): void
    {
        $middleware = new FrontLoaderMiddleware(new MockIndexHandler($this->request));

        $response = $middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
    }
}
