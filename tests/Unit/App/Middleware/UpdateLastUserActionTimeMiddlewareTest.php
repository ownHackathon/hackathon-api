<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware;

use App\Entity\User;
use App\Middleware\UpdateLastUserActionTimeMiddleware;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\Mock\Service\MockUserService;

class UpdateLastUserActionTimeMiddlewareTest extends AbstractMiddleware
{
    private UserService $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = new MockUserService();
    }

    public function testReturnResponseInterface(): void
    {
        $middleware = new UpdateLastUserActionTimeMiddleware($this->userService);

        $user = new User();

        $response = $middleware->process(
            $this->request->withAttribute(User::AUTHENTICATED_USER, $user),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
    }
}
