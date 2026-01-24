<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account;

use Exdrals\Identity\Domain\Account;
use Exdrals\Identity\Domain\AccountActivationInterface;
use Exdrals\Identity\DTO\Account\Account as AccountDTO;
use Exdrals\Identity\DTO\Account\AccountRegistration;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\DuplicateEntryException;
use Shared\Domain\Exception\HttpDuplicateEntryException;
use Shared\Domain\Exception\HttpInvalidArgumentException;
use Shared\Utils\UuidFactoryInterface;

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

        /** @var AccountRegistration $accountData */
        $accountData = $request->getAttribute(AccountRegistration::class);

        if ($activationToken === null) {
            throw new HttpInvalidArgumentException(
                LogMessage::ACTIVATION_TOKEN_MISSING,
                StatusMessage::TOKEN_INVALID,
                [
                    'Token:' => $activationToken,
                ]
            );
        }

        /** @var null|AccountActivationInterface $persistActivationToken */
        $persistActivationToken = $this->accountActivationRepository->findByToken($activationToken);

        if ($persistActivationToken === null) {
            throw new HttpInvalidArgumentException(
                LogMessage::ACTIVATION_TOKEN_MISSING,
                StatusMessage::TOKEN_INVALID,
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
            $accountId = $this->accountRepository->insert($account);
        } catch (DuplicateEntryException $e) {
            throw new HttpDuplicateEntryException(
                LogMessage::ACCOUNT_ALREADY_EXISTS,
                StatusMessage::INVALID_DATA,
                [
                    'E-Mail' => $account->email->toString(),
                    'Exception Message:' => $e->getMessage(),
                ]
            );
        }

        $this->accountActivationRepository->deleteById($persistActivationToken->id);

        $account = $this->accountRepository->findById($accountId);
        $account = AccountDTO::createFromAccount($account);

        return $handler->handle($request->withAttribute(AccountDTO::class, $account));
    }
}
