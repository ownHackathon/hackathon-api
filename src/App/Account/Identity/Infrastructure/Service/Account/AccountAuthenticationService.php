<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuth;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Account\Identity\Domain\Exception\AccountNotFoundException;
use ownHackathon\App\Account\Identity\Domain\Exception\DuplicateAuthException;
use ownHackathon\App\Account\Identity\Domain\Exception\PasswordMismatchException;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\DTO\Account\AuthenticationRequest;
use ownHackathon\App\Account\Identity\DTO\Client\ClientIdentification;
use ownHackathon\App\Account\Identity\DTO\Response\AuthenticationResponse;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\SharedKernel\Domain\Exception\DuplicateEntryException;

readonly final class AccountAuthenticationService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountAccessAuthRepositoryInterface $accountAccessAuthRepository,
        private AuthenticationService $authenticationService,
        private RefreshTokenService $refreshTokenService,
        private AccessTokenService $accessTokenService,
        private AccountService $accountService,
    ) {
    }

    /**
     * @throws AccountNotFoundException
     * @throws PasswordMismatchException
     * @throws DuplicateAuthException
     */
    public function authenticate(AuthenticationRequest $auth, ClientIdentification $clientId): AuthenticationResponse
    {
        $account = $this->accountRepository->findOneByEmail(EmailType::fromString($auth->email));
        if (!($account instanceof AccountInterface)) {
            throw new AccountNotFoundException(email: $auth->email);
        }

        if (!$this->authenticationService->isPasswordMatch($auth->password, $account->password)) {
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
                account: $account->name,
                accountId: $account->id,
                clientId: $clientId->identificationHash,
                errorMessage: $e->getMessage(),
            );
        }

        $this->accountService->updateLastAction($account);

        return AuthenticationResponse::from($accessToken, $refreshToken);
    }
}
