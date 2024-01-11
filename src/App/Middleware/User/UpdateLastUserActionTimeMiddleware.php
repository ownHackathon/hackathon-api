<?php declare(strict_types=1);

namespace App\Middleware\User;

use App\Entity\User;
use App\Service\User\UserService;
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
