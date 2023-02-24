<?php declare(strict_types=1);

namespace App\Test\Middleware;

use App\Middleware\UpdateLastUserActionTimeMiddleware;
use App\Entity\User;
use App\Service\UserService;
use App\Test\Mock\Service\MockUserService;
use Psr\Http\Message\ResponseInterface;

class UpdateLastUserActionTimeMiddlewareTest extends AbstractMiddlewareTest
{
    private readonly UserService $userService;

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
            $this->request->withAttribute(User::USER_ATTRIBUTE, $user),
            $this->handler
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
