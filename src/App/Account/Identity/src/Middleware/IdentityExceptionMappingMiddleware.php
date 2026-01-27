<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware;

use Exdrals\Identity\Domain\Exception\AccountNotFoundException;
use Exdrals\Identity\Domain\Exception\InvalidRefreshTokenException;
use Exdrals\Identity\Domain\Exception\SecurityBreachException;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class IdentityExceptionMappingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (InvalidRefreshTokenException $e) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_NOT_FOUND,
                IdentityStatusMessage::TOKEN_NOT_PERSISTENT,
                [
                    'Refresh Token:' => $e->refreshToken,
                ],
                Level::Warning
            );
        } catch (SecurityBreachException $e) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_CLIENT_MISMATCH,
                IdentityStatusMessage::CLIENT_UNEXPECTED,
                [
                    'expected:' => $e->expectedClientHash,
                    'expected UserAgent' => $e->expectedUserAgent,
                    'current:' => $e->actualClientHash,
                    'current UserAgent:' => $e->actualUserAgent,
                ],
                Level::Warning
            );
        } catch (AccountNotFoundException $e) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'AccessAuth ID:' => $e->accessAuthId,
                    'Account ID:' => $e->accountId,
                ],
                Level::Warning
            );
        }
    }
}
