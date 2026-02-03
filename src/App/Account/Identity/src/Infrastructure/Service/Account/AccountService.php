<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\Domain\Token;
use Exdrals\Identity\DTO\Token\RefreshToken;
use Exdrals\Identity\Infrastructure\Service\Token\PasswordTokenService;
use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Account\AccountAccessAuthInterface;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Enum\Token\TokenType;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;
use Exdrals\Shared\Domain\Token\TokenInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use Exdrals\Shared\Utils\UuidFactoryInterface;
use Monolog\Level;

readonly class AccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountAccessAuthRepositoryInterface $authRepository,
        private TokenRepositoryInterface $tokenRepository,
        private PasswordTokenService $tokenService,
        private UuidFactoryInterface $uuid,
    ) {
    }

    public function sendTokenForPasswordChange(EmailType $email): void
    {
        $account = $this->accountRepository->findByEmail($email);
        $token = $this->createPasswordChangeTokenForUserId($account->id);
        $this->tokenRepository->insert($token);
        $this->tokenService->sendEmail($email, $token);
    }

    public function isEmailAvailable(EmailType $email): bool
    {
        $account = $this->accountRepository->findByEmail($email);

        return $account === null;
    }

    public function createPasswordChangeTokenForUserId(int $userId): TokenInterface
    {
        return new Token(
            id: null,
            accountId: $userId,
            tokenType: TokenType::EMail,
            token: $this->uuid->uuid7(),
            createdAt: new DateTimeImmutable()
        );
    }

    public function cryptPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function updateLastAction(AccountInterface $account): void
    {
        $this->accountRepository->update(
            $account->with(lastActionAt: new DateTimeImmutable())
        );
    }

    public function logout(AccountInterface $account, RefreshToken $refreshToken): void
    {
        $accountAccessAuth = $this->authRepository->findByRefreshToken($refreshToken->refreshToken);

        if (!($accountAccessAuth instanceof AccountAccessAuthInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::LOGOUT_REFRESH_TOKEN_MISMATCH,
                IdentityStatusMessage::UNAUTHORIZED_ACCESS,
                [
                    'accountId' => $account->id,
                    'refreshToken' => $refreshToken->refreshToken,
                ],
                Level::Warning
            );
        }

        if ($account->id !== $accountAccessAuth->accountId) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::LOGOUT_REFRESH_TOKEN_MISMATCH,
                IdentityStatusMessage::UNAUTHORIZED_ACCESS,
                [
                    'accountId' => $account->id,
                    'refreshToken' => $refreshToken->refreshToken,
                ],
                Level::Warning
            );
        }

        $this->authRepository->deleteById($accountAccessAuth->id);
    }
}
