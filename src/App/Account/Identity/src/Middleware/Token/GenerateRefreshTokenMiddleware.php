<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Middleware\Token;

use Exdrals\Account\Identity\DTO\Client\ClientIdentification;
use Exdrals\Account\Identity\DTO\Token\RefreshToken;
use Exdrals\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class GenerateRefreshTokenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RefreshTokenService $tokenService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $clientIdentification = $request->getAttribute(ClientIdentification::class);

        $refreshToken = $this->tokenService->generate($clientIdentification);

        $refreshToken = RefreshToken::fromString($refreshToken);

        return $handler->handle($request->withAttribute(RefreshToken::class, $refreshToken));
    }
}
