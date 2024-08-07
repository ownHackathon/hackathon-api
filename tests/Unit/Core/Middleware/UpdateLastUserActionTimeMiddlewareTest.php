<?php declare(strict_types=1);

namespace Test\Unit\Core\Middleware;

use App\Service\User\UserService;
use Core\Entity\User;
use Core\Middleware\UpdateLastUserActionTimeMiddleware;
use Psr\Http\Message\ResponseInterface;
use Test\Data\Entity\UserTestEntity;
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

        $user = new User(...UserTestEntity::getDefaultUserValue());

        $response = $middleware->process(
            $this->request->withAttribute(User::AUTHENTICATED_USER, $user),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
    }
}
