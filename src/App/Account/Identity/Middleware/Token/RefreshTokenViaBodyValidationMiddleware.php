<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Token;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\DTO\Token\RefreshToken;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Core\Http\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class RefreshTokenViaBodyValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RefreshTokenService $tokenService,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $refreshToken = $request->getParsedBody();

        if (!is_array($refreshToken)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_INVALID,
                IdentityStatusMessage::TOKEN_INVALID,
            );
        }

        $refreshToken = $refreshToken['refreshToken'] ?? null;

        if (!is_string($refreshToken) || $refreshToken === '' || !$this->tokenService->isValid($refreshToken)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_INVALID,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'Refresh Token:' => $refreshToken,
                ],
            );
        }

        $refreshToken = RefreshToken::fromString($refreshToken);

        return $handler->handle($request->withAttribute(RefreshToken::class, $refreshToken));
    }
}
