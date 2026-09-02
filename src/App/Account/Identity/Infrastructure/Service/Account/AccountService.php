<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use Monolog\Level;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthInterface;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\DTO\Token\RefreshToken;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\PasswordTokenService;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\App\Token\Domain\Enum\TokenType;
use ownHackathon\App\Token\Domain\Repository\TokenRepositoryInterface;
use ownHackathon\App\Token\Domain\Token;
use ownHackathon\App\Token\Domain\TokenInterface;
use ownHackathon\Core\Http\Exception\HttpUnauthorizedException;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;

readonly final class AccountService
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
        $account = $this->accountRepository->findOneByEmail($email);
        $token = $this->createPasswordChangeTokenForUserId($account->id);
        $this->tokenRepository->insert($token);
        $this->tokenService->sendEmail($email, $token);
    }

    public function isEmailAvailable(EmailType $email): bool
    {
        $account = $this->accountRepository->findOneByEmail($email);

        return $account === null;
    }

    public function createPasswordChangeTokenForUserId(int $userId): TokenInterface
    {
        return new Token(
            id: null,
            accountId: $userId,
            tokenType: TokenType::EMail,
            token: $this->uuid->uuid7(),
            createdAt: new DateTimeImmutable(),
        );
    }

    public function cryptPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function updateLastAction(AccountInterface $account): void
    {
        $this->accountRepository->update(
            $account->with(lastActionAt: new DateTimeImmutable()),
        );
    }

    public function logout(AccountInterface $account, RefreshToken $refreshToken): void
    {
        $accountAccessAuth = $this->authRepository->findOneByRefreshToken($refreshToken->refreshToken);

        if (!($accountAccessAuth instanceof AccountAccessAuthInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::LOGOUT_REFRESH_TOKEN_MISMATCH,
                IdentityStatusMessage::UNAUTHORIZED_ACCESS,
                [
                    'accountId' => $account->id,
                    'refreshToken' => $refreshToken->refreshToken,
                ],
                Level::Warning,
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
                Level::Warning,
            );
        }

        $this->authRepository->deleteById($accountAccessAuth->id);
    }
}
