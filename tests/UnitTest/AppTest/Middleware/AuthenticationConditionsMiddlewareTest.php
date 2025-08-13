<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use ownHackathon\App\Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware;
use ownHackathon\FunctionalTest\Mock\NullLogger;

class AuthenticationConditionsMiddlewareTest extends AbstractTestMiddleware
{
    private MiddlewareInterface $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new AuthenticationConditionsMiddleware(new NullLogger());
    }

    public function testIsSuccessfull(): void
    {
        $response = $this->middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testRequestIsAuthenticated(): void
    {
        $request = $this->request->withHeader('Authentication', []);

        $response = $this->middleware->process($request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_FORBIDDEN, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', StatusCodeInterface::STATUS_FORBIDDEN);
        $this->assertJsonValueEquals($json, '$.message', 'There is currently successful authentication');
    }

    public function testRequestIsAuthorized(): void
    {
        $request = $this->request->withHeader('Authorization', []);

        $response = $this->middleware->process($request, $this->handler);

        $json = $this->getContentAsJson($response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_FORBIDDEN, $response->getStatusCode());
        $this->assertJsonValueEquals($json, '$.statusCode', StatusCodeInterface::STATUS_FORBIDDEN);
        $this->assertJsonValueEquals($json, '$.message', 'There is currently successful authentication');
    }
}
