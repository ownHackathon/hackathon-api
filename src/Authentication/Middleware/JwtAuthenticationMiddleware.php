<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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
        $token = $request->getHeaderLine('Authorization');
        $token = substr($token, 7);

        $user = null;

        if ($token) {
            $tokenData = JWT::decode($token, new Key($this->tokenSecrect, $this->tokenAlgorithmus));

            $user = $this->userService->findByUuid($tokenData->uuid);
        }

        return $handler->handle($request->withAttribute(User::USER_ATTRIBUTE, $user));
    }
}
