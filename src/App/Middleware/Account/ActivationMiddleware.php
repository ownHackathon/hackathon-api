<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use DateTimeImmutable;
use ownHackathon\App\DTO\AccountRegistrationDTO;
use ownHackathon\App\Entity\Account;
use ownHackathon\Core\Entity\Account\AccountActivationInterface;
use ownHackathon\Core\Exception\DuplicateEntryException;
use ownHackathon\Core\Exception\HttpDuplicateEntryException;
use ownHackathon\Core\Exception\HttpInvalidArgumentException;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountActivationRepositoryInterface;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Utils\UuidFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function password_hash;

use const PASSWORD_BCRYPT;

readonly class ActivationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountActivationRepositoryInterface $accountActivationRepository,
        private AccountRepositoryInterface $accountRepository,
        private UuidFactoryInterface $uuid,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $activationToken = $request->getAttribute('token');

        /** @var AccountRegistrationDTO $accountData */
        $accountData = $request->getAttribute(AccountRegistrationDTO::class);

        if ($activationToken === null) {
            throw new HttpInvalidArgumentException(
                'No activation token was passed.',
                ResponseMessage::TOKEN_INVALID,
                [
                    'Token:' => $activationToken,
                ]
            );
        }

        /** @var null|AccountActivationInterface $persistActivationToken */
        $persistActivationToken = $this->accountActivationRepository->findByToken($activationToken);

        if ($persistActivationToken === null) {
            throw new HttpInvalidArgumentException(
                'Transferred activation key is invalid.',
                ResponseMessage::TOKEN_INVALID,
                [
                    'Invalid activation token:' => $activationToken,
                ]
            );
        }

        $account = new Account(
            id: null,
            uuid: $this->uuid->uuid7(),
            name: $accountData->accountName,
            password: password_hash($accountData->password, PASSWORD_BCRYPT),
            email: $persistActivationToken->email,
            registeredAt: new DateTimeImmutable(),
            lastActionAt: new DateTimeImmutable()
        );

        try {
            $this->accountRepository->insert($account);
        } catch (DuplicateEntryException $e) {
            throw new HttpDuplicateEntryException(
                'Account has already been created.',
                ResponseMessage::DATA_INVALID,
                [
                    'E-Mail' => $account->email->toString(),
                    'Exception Message:' => $e->getMessage(),
                ]
            );
        }

        $this->accountActivationRepository->deleteById($persistActivationToken->id);

        return $handler->handle($request);
    }
}
