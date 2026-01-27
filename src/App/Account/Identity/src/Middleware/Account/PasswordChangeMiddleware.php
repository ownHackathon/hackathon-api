<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account;

use Exdrals\Identity\Domain\Account;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\Infrastructure\Service\Account\AccountService;
use Exdrals\Shared\Domain\Enum\Token\TokenType;
use Exdrals\Shared\Domain\Exception\HttpInvalidArgumentException;
use Exdrals\Shared\Domain\Token\TokenInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class PasswordChangeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TokenRepositoryInterface $tokenRepository,
        private AccountService $accountService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getAttribute('token');
        $password = $request->getParsedBody()['password'];

        if ($token === null) {
            return $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_MISSING, $token);
        }

        $persistedToken = $this->tokenRepository->findByToken($token);

        if (!($persistedToken instanceof TokenInterface) || $persistedToken->tokenType !== TokenType::EMail) {
            return $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_INVALID, $token);
        }

        $account = $this->accountRepository->findById($persistedToken->accountId);

        if (!($account instanceof Account)) {
            return $this->errorResponse(IdentityLogMessage::PASSWORD_CHANGE_TOKEN_ACCOUNT_NOT_FOUND, $token);
        }

        $hashedPassword = $this->accountService->cryptPassword($password);
        $account = $account->with(password: $hashedPassword);

        $this->accountRepository->update($account);
        $this->tokenRepository->deleteById($persistedToken->id);

        return $handler->handle($request);
    }

    private function errorResponse(string $logMessage, ?string $token): ResponseInterface
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
