<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Monolog\Level;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Message\ResponseMessage;
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
                'Client does not match expected client.',
                ResponseMessage::CLIENT_UNEXPECTED,
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
