<?php declare(strict_types=1);

namespace Core\Middleware;

use App\Service\User\UserService;
use Core\Entity\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class UpdateLastUserActionTimeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute(User::AUTHENTICATED_USER);

        if ($user instanceof User) {
            $user = $this->userService->updateLastUserActionTime($user);
        }

        return $handler->handle($request->withAttribute(User::AUTHENTICATED_USER, $user));
    }
}
