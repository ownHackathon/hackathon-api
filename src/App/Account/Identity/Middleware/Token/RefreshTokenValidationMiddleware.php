<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Token;

use Exdrals\Core\Shared\Domain\Exception\HttpUnauthorizedException;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Token\RefreshToken;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class RefreshTokenValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RefreshTokenService $tokenService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $refreshToken = $request->getHeaderLine('Authentication');

        if (!$this->tokenService->isValid($refreshToken)) {
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
