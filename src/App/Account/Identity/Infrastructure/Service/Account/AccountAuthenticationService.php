<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Identity\Domain\AccountAccessAuth;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Domain\Exception\AccountNotFoundException;
use Exdrals\Identity\Domain\Exception\DuplicateAuthException;
use Exdrals\Identity\Domain\Exception\PasswordMismatchException;
use Exdrals\Identity\DTO\Account\AuthenticationRequest;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Response\AuthenticationResponse;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Exdrals\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;

readonly class AccountAuthenticationService
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
            new DateTimeImmutable()
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
