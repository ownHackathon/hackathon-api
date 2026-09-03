<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Token;

use App\Account\Identity\Domain\AccountAccessAuth;
use App\Account\Identity\Domain\AccountAccessAuthInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\DTO\Client\ClientIdentification;
use Core\Http\Exception\HttpUnauthorizedException;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class RefreshTokenMatchClientIdentificationMiddleware implements MiddlewareInterface
{
    #[\Override]
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
                Level::Warning,
            );
        }

        return $handler->handle($request);
    }
}
