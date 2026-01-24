<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Service\Account;

use Exdrals\Mailing\Domain\EmailType;
use DateTimeImmutable;
use Exdrals\Account\Identity\Domain\Token;
use Exdrals\Account\Identity\Domain\TokenInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use Exdrals\Account\Identity\Infrastructure\Service\Token\PasswordTokenService;
use Shared\Domain\Enum\TokenType;
use Shared\Utils\UuidFactoryInterface;

readonly class AccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
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
}
