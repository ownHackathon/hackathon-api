<?php declare(strict_types=1);

namespace App\Middleware\Account;

use App\Entity\Account\Account;
use App\Service\Account\AccountService;
use Core\Entity\Token\TokenInterface;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Enum\TokenType;
use Core\Exception\HttpInvalidArgumentException;
use Core\Repository\Account\AccountRepositoryInterface;
use Core\Repository\Token\TokenRepositoryInterface;
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
            return $this->errorResponse(LogMessage::PASSWORD_CHANGE_TOKEN_MISSING, $token);
        }

        $persistedToken = $this->tokenRepository->findByToken($token);

        if (!($persistedToken instanceof TokenInterface) || $persistedToken->tokenType !== TokenType::EMail) {
            return $this->errorResponse(LogMessage::PASSWORD_CHANGE_TOKEN_INVALID, $token);
        }

        $account = $this->accountRepository->findById($persistedToken->accountId);

        if (!($account instanceof Account)) {
            return $this->errorResponse(LogMessage::PASSWORD_CHANGE_TOKEN_ACCOUNT_NOT_FOUND, $token);
        }

        $hashedPassword = $this->accountService->cryptPassword($password);
        $account = $account->with(password: $hashedPassword);

        $this->accountRepository->update($account);
        $this->tokenRepository->deleteById($persistedToken->id);

        return $handler->handle($request);
    }

    private function errorResponse(LogMessage $logMessage, ?string $token): ResponseInterface
    {
        throw new HttpInvalidArgumentException(
            $logMessage,
            StatusMessage::TOKEN_INVALID,
            [
                'Token:' => $token,
            ]
        );
    }
}
