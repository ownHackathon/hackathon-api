<?php declare(strict_types=1);

namespace Administration\Middleware;

use App\Model\User;
use App\Service\UserService;
use Mezzio\Session\Session;
use Mezzio\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionUserMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Session $session */
        $session = $request->getAttribute(SessionInterface::class);

        $user = null;

        if ($session->has('userId')) {
            $user = $this->userService->findById($session->get('userId'));
        }

        return $handler->handle($request->withAttribute(User::class, $user));
    }
}
