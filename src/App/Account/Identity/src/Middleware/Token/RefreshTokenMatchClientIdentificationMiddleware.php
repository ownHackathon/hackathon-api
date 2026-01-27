<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Token;

use Exdrals\Identity\Domain\AccountAccessAuth;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Shared\Domain\Account\AccountAccessAuthInterface;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class RefreshTokenMatchClientIdentificationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountAccessAuth $accountAccessAuth */
        $accountAccessAuth = $request->getAttribute(AccountAccessAuthInterface::class);

        /** @var ClientIdentification $clientIdentification */
        $clientIdentification = $request->getAttribute(ClientIdentification::class);

        if ($accountAccessAuth->clientIdentHash !== $clientIdentification->identificationHash) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_CLIENT_MISMATCH,
                IdentityStatusMessage::CLIENT_UNEXPECTED,
                [
                    'expected:' => $accountAccessAuth->clientIdentHash,
                    'expected UserAgent' => $accountAccessAuth->userAgent,
                    'current:' => $clientIdentification->identificationHash,
                    'current UserAgent:' => $clientIdentification->clientIdentificationData->userAgent,
                ],
                Level::Warning
            );
        }

        return $handler->handle($request);
    }
}
