<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use ownHackathon\App\DTO\Token\RefreshToken;
use ownHackathon\App\Service\Token\RefreshTokenService;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Message\ResponseMessage;
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
                'Invalid refresh token.',
                ResponseMessage::TOKEN_INVALID,
                [
                    'Refresh Token:' => $refreshToken,
                ],
            );
        }

        $refreshToken = RefreshToken::fromString($refreshToken);

        return $handler->handle($request->withAttribute(RefreshToken::class, $refreshToken));
    }
}
