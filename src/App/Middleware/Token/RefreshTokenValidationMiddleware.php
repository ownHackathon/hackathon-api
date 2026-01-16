<?php declare(strict_types=1);

namespace App\Middleware\Token;

use App\DTO\Token\RefreshToken;
use App\Service\Token\RefreshTokenService;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpUnauthorizedException;
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
                LogMessage::REFRESH_TOKEN_INVALID,
                StatusMessage::TOKEN_INVALID,
                [
                    'Refresh Token:' => $refreshToken,
                ],
            );
        }

        $refreshToken = RefreshToken::fromString($refreshToken);

        return $handler->handle($request->withAttribute(RefreshToken::class, $refreshToken));
    }
}
