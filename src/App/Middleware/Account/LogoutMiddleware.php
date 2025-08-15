<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

readonly class LogoutMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountAccessAuthRepositoryInterface $authRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountInterface|null $account */
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        if (!($account instanceof AccountInterface)) {
            $this->logger->warning('Unauthorized access');

            $message = HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, 'Unauthorized access');
            return new JsonResponse($message, $message->statusCode);
        }

        /** @var ClientIdentification $clientId */
        $clientId = $request->getAttribute(ClientIdentification::class);

        $accountAccessAuth = $this->authRepository->findByAccountIdAndClientIdHash(
            $account->getId(),
            $clientId->identificationHash
        );

        if (!($accountAccessAuth instanceof AccountAccessAuthInterface)) {
            $this->logger->warning('Unauthorized access', [
                'accountId' => $account->getId(),
                'clientIdentificationHash' => $clientId->identificationHash,
            ]);

            $message = HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, 'Unauthorized access');
            return new JsonResponse($message, $message->statusCode);
        }

        $this->authRepository->deleteById($accountAccessAuth->getId());

        return $handler->handle($request);
    }
}
