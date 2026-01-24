<?php declare(strict_types=1);

namespace UnitTest\AppTest\Middleware;

use Exdrals\Identity\Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Shared\Domain\Exception\HttpUnauthorizedException;

class AuthenticationConditionsMiddlewareTest extends AbstractTestMiddleware
{
    private MiddlewareInterface $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new AuthenticationConditionsMiddleware();
    }

    public function testIsSuccessfully(): void
    {
        $response = $this->middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testRequestIsAuthenticated(): void
    {
        $request = $this->request->withHeader('Authentication', []);

        $this->expectException(HttpUnauthorizedException::class);
        $this->middleware->process($request, $this->handler);
    }

    public function testRequestIsAuthorized(): void
    {
        $request = $this->request->withHeader('Authorization', []);

        $this->expectException(HttpUnauthorizedException::class);
        $this->middleware->process($request, $this->handler);
    }
}
