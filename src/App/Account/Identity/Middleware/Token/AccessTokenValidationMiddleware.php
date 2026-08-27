<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Token;

use Monolog\Level;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use ownHackathon\App\Http\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AccessTokenValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessTokenService $tokenService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accessToken = $request->getHeaderLine('Authorization');

        if (empty($accessToken)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::ACCESS_TOKEN_MISSING,
                IdentityStatusMessage::UNAUTHORIZED_ACCESS,
                [],
                Level::Warning
            );
        }

        if (!$this->tokenService->isValid($accessToken)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::ACCESS_TOKEN_EXPIRED,
                IdentityStatusMessage::TOKEN_EXPIRED,
                [
                    'Access Token:' => $accessToken,
                ],
            );
        }

        return $handler->handle($request);
    }
}
