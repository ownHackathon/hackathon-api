<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Token;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use Core\Http\Exception\HttpUnauthorizedException;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class AccessTokenValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessTokenService $tokenService,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accessToken = $request->getHeaderLine('Authorization');

        if ($accessToken === '') {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::ACCESS_TOKEN_MISSING,
                IdentityStatusMessage::UNAUTHORIZED_ACCESS,
                [],
                Level::Warning,
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
