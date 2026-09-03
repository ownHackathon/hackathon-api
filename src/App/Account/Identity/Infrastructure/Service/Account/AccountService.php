<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Account;

use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\DTO\Token\RefreshToken;
use App\Account\Identity\Infrastructure\Service\Token\PasswordTokenService;
use App\Mailing\Domain\EmailType;
use App\Token\Domain\Enum\TokenType;
use App\Token\Domain\Repository\TokenRepositoryInterface;
use App\Token\Domain\Token;
use App\Token\Domain\TokenInterface;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use Monolog\Level;
use Psr\Log\LoggerInterface;

readonly final class AccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountAccessAuthRepositoryInterface $authRepository,
        private TokenRepositoryInterface $tokenRepository,
        private PasswordTokenService $tokenService,
        private UuidFactoryInterface $uuid,
        private LoggerInterface $activityLogger,
    ) {
    }

    public function sendTokenForPasswordChange(EmailType $email): void
    {
        $account = $this->accountRepository->findOneByEmail($email);
        $token = $this->createPasswordChangeTokenForUserId($account->id);
        $this->tokenRepository->insert($token);
        $this->tokenService->sendEmail($email, $token);

        $this->activityLogger->info(
            IdentityLogMessage::ACTIVITY_PASSWORD_CHANGE_REQUESTED,
            [
                'accountId' => $account->id,
                'accountUuid' => $account->uuid->toString(),
            ],
        );
    }

    public function isEmailAvailable(EmailType $email): bool
    {
        try {
            $this->accountRepository->findOneByEmail($email);
        } catch (EmptyResultException) {
            return true;
        }

        return false;
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
        try {
            $accountAccessAuth = $this->authRepository->findOneByRefreshToken($refreshToken->refreshToken);
        } catch (EmptyResultException) {
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

        $this->activityLogger->info(
            IdentityLogMessage::ACTIVITY_LOGOUT,
            [
                'accountId' => $account->id,
                'accountUuid' => $account->uuid->toString(),
            ],
        );
    }
}
