<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Message\ResponseMessage;

readonly class RefreshTokenMatchClientIdentificationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountAccessAuth $accountAccessAuth */
        $accountAccessAuth = $request->getAttribute(AccountAccessAuth::class);

        /** @var ClientIdentification $clientIdentification */
        $clientIdentification = $request->getAttribute(ClientIdentification::class);

        if ($accountAccessAuth->getClientIdentHash() !== $clientIdentification->identificationHash) {
            return new JsonResponse(
                HttpFailureMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::CLIENT_UNEXPECTED),
                HTTP::STATUS_UNAUTHORIZED
            );
        }

        return $handler->handle($request);
    }
}
