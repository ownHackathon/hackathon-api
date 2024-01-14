<?php declare(strict_types=1);

namespace App\Middleware\Authentication;

use App\Entity\User;
use App\Service\User\UserService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function substr;

readonly class JwtAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
        private string $tokenSecret,
        private string $tokenAlgorithmus,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Authorization');
        $token = substr($token, 7);

        $user = null;

        if ($token) {
            try {
                $tokenData = JWT::decode($token, new Key($this->tokenSecret, $this->tokenAlgorithmus));
            } catch (ExpiredException $e) {
                $this->logger->notice(
                    '{Host} has call {URI} with expired Token',
                    [
                        'Host' => $request->getServerParams()['HTTP_HOST'],
                        'URI' => $request->getServerParams()['REQUEST_URI'],
                    ]
                );
                return new JsonResponse(['message' => 'invalid Token'], HTTP::STATUS_UNAUTHORIZED);
            }

            $user = $this->userService->findByUuid($tokenData->uuid);
        }

        $this->logger->info('{Host} as {User} call -> {URI}', [
            'Host' => $request->getServerParams()['REMOTE_ADDR'],
            'User' => $user ? $user->getName() : 'Guest',
            'URI' => $request->getServerParams()['REQUEST_URI'],
            'Query' => $request->getQueryParams(),
        ]);

        return $handler->handle($request->withAttribute(User::AUTHENTICATED_USER, $user));
    }
}
