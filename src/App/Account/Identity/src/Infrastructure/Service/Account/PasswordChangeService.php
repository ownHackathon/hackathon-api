<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Account;

use Exdrals\Identity\Domain\Account;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Account\AccountPassword;
use Exdrals\Identity\DTO\Token\Token;
use Exdrals\Shared\Domain\Enum\Token\TokenType;
use Exdrals\Shared\Domain\Exception\HttpInvalidArgumentException;
use Exdrals\Shared\Domain\Token\TokenInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;

readonly class PasswordChangeService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TokenRepositoryInterface $tokenRepository,
        private AccountService $accountService,
    ) {
    }

    public function change(Token $token, AccountPassword $password): void
    {
        if ($token->token === null) {
            $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_MISSING, $token->token);
        }

        $persistedToken = $this->tokenRepository->findByToken($token->token);

        if (!($persistedToken instanceof TokenInterface) || $persistedToken->tokenType !== TokenType::EMail) {
            $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_INVALID, $token->token);
        }

        $account = $this->accountRepository->findById($persistedToken->accountId);

        if (!($account instanceof Account)) {
            $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_ACCOUNT_NOT_FOUND, $token->token);
        }

        $hashedPassword = $this->accountService->cryptPassword($password->password);
        $account = $account->with(password: $hashedPassword);

        $this->accountRepository->update($account);
        $this->tokenRepository->deleteById($persistedToken->id);
    }

    private function errorResponse(string $logMessage, ?string $token): void
    {
        throw new HttpInvalidArgumentException(
            $logMessage,
            IdentityStatusMessage::TOKEN_INVALID,
            [
                'Token:' => $token,
            ]
        );
    }
}
