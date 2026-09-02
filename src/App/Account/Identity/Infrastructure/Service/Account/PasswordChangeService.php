<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Account;

use ownHackathon\App\Account\Identity\Domain\Account;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\DTO\Account\AccountPassword;
use ownHackathon\App\Token\Domain\Enum\TokenType;
use ownHackathon\App\Token\Domain\Repository\TokenRepositoryInterface;
use ownHackathon\App\Token\Domain\TokenInterface;
use ownHackathon\App\Token\DTO\Token;
use ownHackathon\Core\Http\Exception\HttpInvalidArgumentException;
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

        $persistedToken = $this->tokenRepository->findOneByToken($token->token);

        if (!($persistedToken instanceof TokenInterface) || $persistedToken->tokenType !== TokenType::EMail) {
            $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_INVALID, $token->token);
        }

        $account = $this->accountRepository->findOneById($persistedToken->accountId);

        if (!($account instanceof Account)) {
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

    private function errorResponse(string $logMessage, ?string $token): void
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
