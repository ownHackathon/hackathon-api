<?php declare(strict_types=1);

namespace AppTest\Middleware;

use App\Middleware\UserMiddleware;
use App\Model\User;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;

class UserMiddlewareTest extends AbstractMiddlewareTest
{
    public function testReturnResponseInterface(): void
    {
        $service = $this->createMock(UserService::class);
        $user = new User();

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('userUuid')
            ->willReturn('A3953212-23ed-3a79-cb02-215fe2a9bd6a');

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with(User::class, $user)
            ->willReturn($this->request);

        $service->expects($this->once())
            ->method('findByUuid')
            ->with('A3953212-23ed-3a79-cb02-215fe2a9bd6a')
            ->willReturn($user);

        $middleware = new UserMiddleware($service);

        $response = $middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
