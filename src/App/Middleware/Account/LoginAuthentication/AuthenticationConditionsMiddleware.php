<?php declare(strict_types=1);

namespace App\Middleware\Account\LoginAuthentication;

use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpUnauthorizedException;
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
                LogMessage::LOGIN_DENIED_AUTH_HEADER_ALREADY_PRESENT,
                StatusMessage::ACCOUNT_ALREADY_AUTHENTICATED,
                [
                    'uri' => (string)$request->getUri(),
                    'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
                ]
            );
        }

        return $handler->handle($request);
    }
}
