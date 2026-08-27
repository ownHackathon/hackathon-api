<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use InvalidArgumentException;
use ownHackathon\App\Account\Identity\Domain\Account;
use ownHackathon\App\Account\Identity\Domain\AccountActivationInterface;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountActivationRepositoryInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\DTO\Account\Account as AccountDTO;
use ownHackathon\App\Account\Identity\DTO\Account\AccountRegistration;
use ownHackathon\Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use ownHackathon\App\Http\Exception\HttpDuplicateEntryException;
use ownHackathon\App\Http\Exception\HttpInvalidArgumentException;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;

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

        $persistActivationToken = $this->accountActivationRepository->findOneByToken($activationToken);

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

        $account = $this->accountRepository->findOneById($accountId);
        return AccountDTO::createFromAccount($account);
    }
}
