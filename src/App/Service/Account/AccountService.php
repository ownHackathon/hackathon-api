<?php declare(strict_types=1);

namespace ownHackathon\App\Service\Account;

use DateTimeImmutable;
use ownHackathon\App\Entity\Token;
use ownHackathon\App\Enum\TokenType;
use ownHackathon\App\Service\Token\PasswordTokenService;
use ownHackathon\Core\Entity\TokenInterface;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Repository\TokenRepositoryInterface;
use ownHackathon\Core\Type\Email;
use ownHackathon\Core\Utils\UuidFactoryInterface;

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
        $token = $this->createPasswordChangeTokenForUserId($account->getId());
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
