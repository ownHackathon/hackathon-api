<?php declare(strict_types=1);

namespace App\Middleware\Token;

use App\DTO\Token\RefreshToken;
use Core\Entity\Account\AccountAccessAuthInterface;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpUnauthorizedException;
use Core\Repository\Account\AccountAccessAuthRepositoryInterface;
use Monolog\Level;
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
