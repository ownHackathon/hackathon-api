<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Account\Authentication;

use ownHackathon\App\Http\Exception\HttpUnauthorizedException;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class AuthenticationConditionsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->hasHeader('Authentication') || $request->hasHeader('Authorization')) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::LOGIN_DENIED_AUTH_HEADER_ALREADY_PRESENT,
                IdentityStatusMessage::ACCOUNT_ALREADY_AUTHENTICATED,
                [
                    'uri' => (string)$request->getUri(),
                    'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
                ]
            );
        }

        return $handler->handle($request);
    }
}
