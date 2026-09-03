<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Account\Authentication;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use Core\Http\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class AuthenticationConditionsMiddleware implements MiddlewareInterface
{
    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->hasHeader('Authentication') || $request->hasHeader('Authorization')) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::LOGIN_DENIED_AUTH_HEADER_ALREADY_PRESENT,
                IdentityStatusMessage::ACCOUNT_ALREADY_AUTHENTICATED,
                [
                    'uri' => (string)$request->getUri(),
                    'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
                ],
            );
        }

        return $handler->handle($request);
    }
}
