<?php declare(strict_types=1);

namespace App\Service\Account;

use DateTimeImmutable;
use App\Entity\Token\Token;
use App\Service\Token\PasswordTokenService;
use Core\Entity\Token\TokenInterface;
use Core\Enum\TokenType;
use Core\Repository\AccountRepositoryInterface;
use Core\Repository\TokenRepositoryInterface;
use Core\Type\Email;
use Core\Utils\UuidFactoryInterface;

readonly class AccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TokenRepositoryInterface $tokenRepository,
        private PasswordTokenService $tokenService,
        private UuidFactoryInterface $uuid,
    ) {
    }

    public function sendTokenForPasswordChange(Email $email): void
    {
        $account = $this->accountRepository->findByEmail($email);
        $token = $this->createPasswordChangeTokenForUserId($account->id);
        $this->tokenRepository->insert($token);
        $this->tokenService->sendEmail($email, $token);
    }

    public function isEmailAvailable(Email $email): bool
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
