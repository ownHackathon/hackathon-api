<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\User;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userUuid = $request->getAttribute('userUuid');

        $user = $this->userService->findByUuid($userUuid);

        return $handler->handle($request->withAttribute(User::class, $user));
    }
}
