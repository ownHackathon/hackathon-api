<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Token;

use App\Account\Identity\Domain\AccountAccessAuthInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use App\Account\Identity\DTO\Token\RefreshToken;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class RefreshTokenDatabaseExistenceMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountAccessAuthRepositoryInterface $accessAuthRepository,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RefreshToken $refreshToken */
        $refreshToken = $request->getAttribute(RefreshToken::class);

        try {
            $persistToken = $this->accessAuthRepository->findOneByRefreshToken($refreshToken->refreshToken);
        } catch (EmptyResultException) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_NOT_FOUND,
                IdentityStatusMessage::TOKEN_NOT_PERSISTENT,
                [
                    'Refresh Token:' => $refreshToken,
                ],
                Level::Warning,
            );
        }

        return $handler->handle($request->withAttribute(AccountAccessAuthInterface::class, $persistToken));
    }
}
