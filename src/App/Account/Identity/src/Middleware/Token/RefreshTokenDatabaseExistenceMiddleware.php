<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Token;

use Exdrals\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Identity\DTO\Token\RefreshToken;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpUnauthorizedException;

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
            throw new HttpUnauthorizedException(
                LogMessage::REFRESH_TOKEN_NOT_FOUND,
                StatusMessage::TOKEN_NOT_PERSISTENT,
                [
                    'Refresh Token:' => $refreshToken,
                ],
                Level::Warning
            );
        }

        return $handler->handle($request->withAttribute(AccountAccessAuthInterface::class, $persistToken));
    }
}
