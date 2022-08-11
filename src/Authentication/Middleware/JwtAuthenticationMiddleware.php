<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Exception;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function substr;

class JwtAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UserService $userService,
        private readonly string $tokenSecrect,
        private readonly string $tokenAlgorithmus
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Authorization');
        $token = substr($token, 7);

        $user = null;

        if ($token) {
            try {
                $tokenData = JWT::decode($token, new Key($this->tokenSecrect, $this->tokenAlgorithmus));
            } catch (Exception $e) {
                return new JsonResponse(['message' => 'invalid Token'], HTTP::STATUS_UNAUTHORIZED);
            }

            $user = $this->userService->findByUuid($tokenData->uuid);
        }

        return $handler->handle($request->withAttribute(User::USER_ATTRIBUTE, $user));
    }
}
