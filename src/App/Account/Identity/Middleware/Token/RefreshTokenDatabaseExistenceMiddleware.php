<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Token;

use Monolog\Level;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthInterface;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\App\Account\Identity\DTO\Token\RefreshToken;
use ownHackathon\Core\Shared\Domain\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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

        $persistToken = $this->accessAuthRepository->findOneByRefreshToken($refreshToken->refreshToken);
        if (!($persistToken instanceof AccountAccessAuthInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_NOT_FOUND,
                IdentityStatusMessage::TOKEN_NOT_PERSISTENT,
                [
                    'Refresh Token:' => $refreshToken,
                ],
                Level::Warning
            );
        }

        return $handler->handle($request->withAttribute(AccountAccessAuthInterface::class, $persistToken));
    }
}
