<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Account;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\DTO\Account\AccountPassword;
use App\Token\Domain\Enum\TokenType;
use App\Token\Domain\Repository\TokenRepositoryInterface;
use App\Token\DTO\Token;
use Core\Http\Exception\HttpInvalidArgumentException;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Psr\Log\LoggerInterface;

readonly final class PasswordChangeService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TokenRepositoryInterface $tokenRepository,
        private AccountService $accountService,
        private LoggerInterface $activityLogger,
    ) {
    }

    public function change(Token $token, AccountPassword $password): void
    {
        if ($token->token === null) {
            $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_MISSING, $token->token);
        }

        try {
            $persistedToken = $this->tokenRepository->findOneByToken($token->token);
        } catch (EmptyResultException) {
            $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_INVALID, $token->token);
        }

        if ($persistedToken->tokenType !== TokenType::EMail) {
            $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_INVALID, $token->token);
        }

        try {
            $account = $this->accountRepository->findOneById($persistedToken->accountId);
        } catch (EmptyResultException) {
            $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_ACCOUNT_NOT_FOUND, $token->token);
        }

        $hashedPassword = $this->accountService->cryptPassword($password->password);
        $account = $account->with(password: $hashedPassword);

        $this->accountRepository->update($account);
        $this->tokenRepository->deleteById($persistedToken->id);

        $this->activityLogger->info(
            IdentityLogMessage::ACTIVITY_PASSWORD_CHANGED,
            [
                'accountId' => $account->id,
                'accountUuid' => $account->uuid->toString(),
            ],
        );
    }

    private function errorResponse(string $logMessage, ?string $token): never
    {
        throw new HttpInvalidArgumentException(
            $logMessage,
            IdentityStatusMessage::TOKEN_INVALID,
            [
                'Token:' => $token,
            ],
        );
    }
}
