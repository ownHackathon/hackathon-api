<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\AppTest\Middleware;

use ownHackathon\App\Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthenticationConditionsMiddlewareTest extends AbstractTestMiddleware
{
    private MiddlewareInterface $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new AuthenticationConditionsMiddleware();
    }

    public function testIsSuccessfull(): void
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
