<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Middleware\Token;

use Exdrals\Account\Identity\Domain\AccountAccessAuth;
use Exdrals\Account\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Account\Identity\DTO\Client\ClientIdentification;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpUnauthorizedException;

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
                LogMessage::REFRESH_TOKEN_CLIENT_MISMATCH,
                StatusMessage::CLIENT_UNEXPECTED,
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
