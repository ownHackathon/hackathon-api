<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use Exdrals\Identity\Domain\Account;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Account\Account as AccountDTO;
use Exdrals\Identity\DTO\Account\AccountRegistration;
use Exdrals\Shared\Domain\Account\AccountActivationInterface;
use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Shared\Domain\Exception\HttpDuplicateEntryException;
use Exdrals\Shared\Domain\Exception\HttpInvalidArgumentException;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Shared\Utils\UuidFactoryInterface;
use InvalidArgumentException;

readonly class AccountCreatorService
{
    public function __construct(
        private AccountActivationRepositoryInterface $accountActivationRepository,
        private AccountRepositoryInterface $accountRepository,
        private UuidFactoryInterface $uuid,
    ) {
    }

    public function create(AccountRegistration $accountRegistration, ?string $activationToken): AccountDTO
    {
        if ($activationToken === null) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACTIVATION_TOKEN_MISSING,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'Token:' => $activationToken,
                ]
            );
        }

        $persistActivationToken = $this->accountActivationRepository->findByToken($activationToken);

        if (!$persistActivationToken instanceof AccountActivationInterface) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACTIVATION_TOKEN_MISSING,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'Invalid activation token:' => $activationToken,
                ]
            );
        }

        $account = new Account(
            id: null,
            uuid: $this->uuid->uuid7(),
            name: $accountRegistration->accountName,
            password: password_hash($accountRegistration->password, PASSWORD_BCRYPT),
            email: $persistActivationToken->email,
            registeredAt: new DateTimeImmutable(),
            lastActionAt: new DateTimeImmutable()
        );

        try {
            $accountId = $this->accountRepository->insert($account);
        } catch (DuplicateEntryException $e) {
            throw new HttpDuplicateEntryException(
                IdentityLogMessage::ACCOUNT_ALREADY_EXISTS,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'E-Mail' => $account->email->toString(),
                    'Exception Message:' => $e->getMessage(),
                ]
            );
        }

        try {
            $this->accountActivationRepository->deleteById($persistActivationToken->id);
        } catch (InvalidArgumentException $exception) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACCOUNT_UPDATE_UNKNOWN_ERROR,
                IdentityStatusMessage::UNKNOWN_ERROR,
                [
                    'account' => $account->name,
                    'exception' => $exception,
                ]
            );
        }

        $account = $this->accountRepository->findById($accountId);
        return AccountDTO::createFromAccount($account);
    }
}
