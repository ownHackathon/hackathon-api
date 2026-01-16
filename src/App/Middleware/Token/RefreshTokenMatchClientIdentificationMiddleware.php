<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Message\ResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

readonly class RefreshTokenMatchClientIdentificationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountAccessAuth $accountAccessAuth */
        $accountAccessAuth = $request->getAttribute(AccountAccessAuthInterface::class);

        /** @var ClientIdentification $clientIdentification */
        $clientIdentification = $request->getAttribute(ClientIdentification::class);

        if ($accountAccessAuth->getClientIdentHash() !== $clientIdentification->identificationHash) {
            $this->logger->alert('Client does not match expected client', [
                'expected:' => $accountAccessAuth->getClientIdentHash(),
                'expected UserAgent' => $accountAccessAuth->getUserAgent(),
                'current:' => $clientIdentification->identificationHash,
                'current UserAgent:' => $clientIdentification->clientIdentificationData->userAgent,
            ]);

            return new JsonResponse(
                HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::CLIENT_UNEXPECTED),
                HTTP::STATUS_UNAUTHORIZED
            );
        }

        return $handler->handle($request);
    }
}
