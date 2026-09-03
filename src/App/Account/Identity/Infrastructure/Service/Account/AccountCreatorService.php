<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Account;

use App\Account\Identity\Application\Port\ActivityLoggerInterface;
use App\Account\Identity\Application\Port\EmailHashSaltProviderInterface;
use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\Domain\Repository\AccountActivationRepositoryInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\DTO\Account\Account as AccountDTO;
use App\Account\Identity\DTO\Account\AccountRegistration;
use Core\Http\Exception\HttpDuplicateEntryException;
use Core\Http\Exception\HttpInvalidArgumentException;
use Core\Observability\EmailHasher;
use Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;

readonly final class AccountCreatorService
{
    public function __construct(
        private AccountActivationRepositoryInterface $accountActivationRepository,
        private AccountRepositoryInterface $accountRepository,
        private UuidFactoryInterface $uuid,
        private ActivityLoggerInterface $activityLogger,
        private EmailHashSaltProviderInterface $emailHashSaltProvider,
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
                ],
            );
        }

        try {
            $persistActivationToken = $this->accountActivationRepository->findOneByToken($activationToken);
        } catch (EmptyResultException) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACTIVATION_TOKEN_MISSING,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'Invalid activation token:' => $activationToken,
                ],
            );
        }

        $account = new Account(
            id: null,
            uuid: $this->uuid->uuid7(),
            name: $accountRegistration->accountName,
            password: password_hash($accountRegistration->password, PASSWORD_BCRYPT),
            email: $persistActivationToken->email,
            registeredAt: new DateTimeImmutable(),
            lastActionAt: new DateTimeImmutable(),
        );

        try {
            $accountId = $this->accountRepository->insert($account);
        } catch (DuplicateEntryException $e) {
            throw new HttpDuplicateEntryException(
                IdentityLogMessage::ACCOUNT_ALREADY_EXISTS,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'emailHash' => EmailHasher::hash($account->email->toString(), $this->emailHashSaltProvider->salt()),
                    'Exception Message:' => $e->getMessage(),
                ],
            );
        }

        try {
            $this->accountActivationRepository->deleteById($persistActivationToken->id);
        } catch (InvalidArgumentException $exception) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACCOUNT_UPDATE_UNKNOWN_ERROR,
                IdentityStatusMessage::UNKNOWN_ERROR,
                [
                    'accountUuid' => $account->uuid->toString(),
                    'exception' => $exception,
                ],
            );
        }

        $account = $this->accountRepository->findOneById($accountId);

        $this->activityLogger->info(
            IdentityLogMessage::ACTIVITY_ACCOUNT_ACTIVATED,
            [
                'accountId' => $account->id,
                'accountUuid' => $account->uuid->toString(),
            ],
        );

        return AccountDTO::createFromAccount($account);
    }
}
