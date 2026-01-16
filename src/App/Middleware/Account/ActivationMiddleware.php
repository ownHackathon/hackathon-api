<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use DateTimeImmutable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ownHackathon\App\DTO\AccountRegistrationDTO;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\App\Entity\Account;
use ownHackathon\Core\Entity\Account\AccountActivationInterface;
use ownHackathon\Core\Exception\DuplicateEntryException;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountActivationRepositoryInterface;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Utils\UuidFactoryInterface;

use function password_hash;

use const PASSWORD_BCRYPT;

readonly class ActivationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountActivationRepositoryInterface $accountActivationRepository,
        private AccountRepositoryInterface $accountRepository,
        private UuidFactoryInterface $uuid,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $activationToken = $request->getAttribute('token');

        /** @var AccountRegistrationDTO $accountData */
        $accountData = $request->getAttribute(AccountRegistrationDTO::class);

        if ($activationToken === null) {
            $this->logger->notice('No activation token was passed.', [
                'Token:' => $activationToken,
            ]);

            $message = HttpResponseMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::TOKEN_INVALID);

            return new JsonResponse($message, HTTP::STATUS_BAD_REQUEST);
        }

        /** @var null|AccountActivationInterface $persistActivationToken */
        $persistActivationToken = $this->accountActivationRepository->findByToken($activationToken);

        if ($persistActivationToken === null) {
            $this->logger->notice('Transferred activation key is invalid.', [
                'Invalid activation token:' => $activationToken,
            ]);

            $message = HttpResponseMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::TOKEN_INVALID);

            return new JsonResponse($message, HTTP::STATUS_BAD_REQUEST);
        }

        $account = new Account(
            id: null,
            uuid: $this->uuid->uuid7(),
            name: $accountData->accountName,
            password: password_hash($accountData->password, PASSWORD_BCRYPT),
            email: $persistActivationToken->getEmail(),
            registeredAt: new DateTimeImmutable(),
            lastActionAt: new DateTimeImmutable()
        );

        try {
            $this->accountRepository->insert($account);
        } catch (DuplicateEntryException $e) {
            $this->logger->notice('Account has already been created.', [
                'E-Mail' => $account->getEmail()->toString(),
                'Exception Message:' => $e->getMessage(),
            ]);

            $message = HttpResponseMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::DATA_INVALID);

            return new JsonResponse($message, HTTP::STATUS_BAD_REQUEST);
        }

        $this->accountActivationRepository->deleteById($persistActivationToken->getId());

        return $handler->handle($request);
    }
}
