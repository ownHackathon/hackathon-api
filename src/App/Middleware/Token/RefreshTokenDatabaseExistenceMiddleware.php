<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\App\DTO\RefreshToken;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;

readonly class RefreshTokenDatabaseExistenceMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountAccessAuthRepositoryInterface $accessAuthRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RefreshToken $refreshToken */
        $refreshToken = $request->getAttribute(RefreshToken::class);

        $persistToken = $this->accessAuthRepository->findByRefreshToken($refreshToken->refreshToken);
        if (!($persistToken instanceof AccountAccessAuthInterface)) {
            return new JsonResponse(
                HttpFailureMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::TOKEN_NOT_PERSISTENT),
                HTTP::STATUS_UNAUTHORIZED
            );
        }

        return $handler->handle($request->withAttribute(AccountAccessAuth::class, $persistToken));
    }
}
