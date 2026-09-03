<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Account;

use App\Account\Identity\Application\Port\ActivityLoggerInterface;
use App\Account\Identity\Application\Port\EmailHashSaltProviderInterface;
use App\Account\Identity\Domain\AccountAccessAuth;
use App\Account\Identity\Domain\Exception\AccountNotFoundException;
use App\Account\Identity\Domain\Exception\DuplicateAuthException;
use App\Account\Identity\Domain\Exception\PasswordMismatchException;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\DTO\Account\AuthenticationRequest;
use App\Account\Identity\DTO\Client\ClientIdentification;
use App\Account\Identity\DTO\Response\AuthenticationResponse;
use App\Account\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use App\Mailing\Domain\EmailType;
use Core\Observability\EmailHasher;
use Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use DateTimeImmutable;

readonly final class AccountAuthenticationService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountAccessAuthRepositoryInterface $accountAccessAuthRepository,
        private AuthenticationService $authenticationService,
        private RefreshTokenService $refreshTokenService,
        private AccessTokenService $accessTokenService,
        private AccountService $accountService,
        private ActivityLoggerInterface $activityLogger,
        private EmailHashSaltProviderInterface $emailHashSaltProvider,
    ) {
    }

    /**
     * @throws AccountNotFoundException
     * @throws PasswordMismatchException
     * @throws DuplicateAuthException
     */
    public function authenticate(AuthenticationRequest $auth, ClientIdentification $clientId): AuthenticationResponse
    {
        try {
            $account = $this->accountRepository->findOneByEmail(EmailType::fromString($auth->email));
        } catch (EmptyResultException) {
            $this->activityLogger->warning(
                IdentityLogMessage::ACTIVITY_LOGIN_FAILED,
                [
                    'emailHash' => EmailHasher::hash($auth->email, $this->emailHashSaltProvider->salt()),
                    'clientIdentHash' => $clientId->identificationHash,
                    'reason' => 'account_not_found',
                ],
            );
            throw new AccountNotFoundException(email: $auth->email);
        }

        if (!$this->authenticationService->isPasswordMatch($auth->password, $account->password)) {
            $this->activityLogger->warning(
                IdentityLogMessage::ACTIVITY_LOGIN_FAILED,
                [
                    'accountId' => $account->id,
                    'accountUuid' => $account->uuid->toString(),
                    'clientIdentHash' => $clientId->identificationHash,
                    'reason' => 'password_mismatch',
                ],
            );
            throw new PasswordMismatchException(email: $auth->email);
        }

        $refreshToken = $this->refreshTokenService->generate($clientId);
        $accessToken = $this->accessTokenService->generate($account->uuid);

        $accountAccessAuth = new AccountAccessAuth(
            null,
            $account->id,
            'default',
            $refreshToken->refreshToken,
            $clientId->clientIdentificationData->userAgent,
            $clientId->identificationHash,
            new DateTimeImmutable(),
        );

        try {
            $this->accountAccessAuthRepository->insert($accountAccessAuth);
        } catch (DuplicateEntryException $e) {
            throw new DuplicateAuthException(
                account: $account->uuid->toString(),
                accountId: $account->id,
                clientId: $clientId->identificationHash,
                errorMessage: $e->getMessage(),
            );
        }

        $this->activityLogger->info(
            IdentityLogMessage::ACTIVITY_LOGIN_SUCCESS,
            [
                'accountId' => $account->id,
                'accountUuid' => $account->uuid->toString(),
                'clientIdentHash' => $clientId->identificationHash,
            ],
        );

        $this->accountService->updateLastAction($account);

        return AuthenticationResponse::from($accessToken, $refreshToken);
    }
}
