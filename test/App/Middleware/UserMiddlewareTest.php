<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\User;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserMiddlewareTest extends TestCase
{
    public function testReturnResponseInterface()
    {
        $service = $this->createMock(UserService::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $user = new User();

        $request->expects($this->once())
            ->method('getAttribute')
            ->with('userId')
            ->willReturn(1);

        $request->expects($this->once())
            ->method('withAttribute')
            ->with(User::class, $user)
            ->willReturn($request);

        $service->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($user);

        $middleware = new UserMiddleware($service);

        $response = $middleware->process($request, $handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
