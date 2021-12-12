<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Mezzio\Session\Session;
use Mezzio\Session\SessionInterface;
use Mezzio\Session\SessionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
        private string $tokenSecrect,
        private string $tokenAlgorithmus
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Session $session */
        $session = $request->getAttribute(SessionInterface::class);

        $user = null;

        if ($session->has('token')) {
            $token = $session->get('token');

            try {
                $token = JWT::decode($token, $this->tokenSecrect, [$this->tokenAlgorithmus]);
            } catch (ExpiredException $exception) {
                /** @var SessionInterface $session */
                $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
                $session->unset('token');
                return $handler->handle(($request->withAttribute(User::USER_ATTRIBUTE, null)));
            }

            $user = $this->userService->findById($token->id);
        }

        return $handler->handle($request->withAttribute(User::USER_ATTRIBUTE, $user));
    }
}
