<?php declare(strict_types=1);

namespace App\Middleware;

use App\Entity\User;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UpdateLastUserActionTimeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute(User::USER_ATTRIBUTE);

        if ($user instanceof User) {
            $user = $this->userService->updateLastUserActionTime($user);
        }

        return $handler->handle($request->withAttribute(User::USER_ATTRIBUTE, $user));
    }
}
