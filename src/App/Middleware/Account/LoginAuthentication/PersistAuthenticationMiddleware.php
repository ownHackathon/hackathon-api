<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use DateTimeImmutable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\App\DTO\RefreshToken;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Exception\DuplicateEntryException;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;

readonly class PersistAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountAccessAuthRepositoryInterface $repository,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountInterface $account */
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        /** @var ClientIdentification $clientIdent */
        $clientIdent = $request->getAttribute(ClientIdentification::class);

        /** @var RefreshToken $refreshToken */
        $refreshToken = $request->getAttribute(RefreshToken::class);

        // @phpstan-ignore-next-line
        if ($account === null || $clientIdent === null || $refreshToken === null) {
            $this->logger->notice('Authentication could not be permanently stored due to missing data.', [
                // @phpstan-ignore-next-line
                'Account:' => isset($account) ? $account->getEMail() : null,
                // @phpstan-ignore-next-line
                'Client ID:' => $clientIdent ? $clientIdent->identificationHash : null,
                // @phpstan-ignore-next-line
                'Refresh Token:' => $refreshToken ? 'placed' : null,
            ]);
            $message = HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::DATA_INVALID);

            return new JsonResponse($message, $message->statusCode);
        }

        $accountAccessAuth = new AccountAccessAuth(
            1,
            $account->getId(),
            'default',
            $refreshToken->refreshToken,
            $clientIdent->clientIdentificationData->userAgent,
            $clientIdent->identificationHash,
            new DateTimeImmutable()
        );
        try {
            $this->repository->insert($accountAccessAuth);
        } catch (DuplicateEntryException $e) {
            $this->logger->warning('Login is not possible due to a duplicate login from the same source.', [
                'Account' => $account->getName(),
                'ClientID' => $clientIdent->identificationHash,
                'ErrorMessage' => $e->getMessage(),
            ]);

            $message = HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::DATA_INVALID);

            return new JsonResponse($message, $message->statusCode);
        }

        return $handler->handle($request);
    }
}
