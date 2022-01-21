<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\User;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;

class UserMiddlewareTest extends AbstractMiddlewareTest
{
    public function testReturnResponseInterface()
    {
        $service = $this->createMock(UserService::class);
        $user = new User();

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('userId')
            ->willReturn(1);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with(User::class, $user)
            ->willReturn($this->request);

        $service->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($user);

        $middleware = new UserMiddleware($service);

        $response = $middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
