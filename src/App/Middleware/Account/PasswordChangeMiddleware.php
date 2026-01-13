<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use ownHackathon\App\Entity\Account;
use ownHackathon\App\Service\Account\AccountService;
use ownHackathon\Core\Entity\TokenInterface;
use ownHackathon\Core\Enum\TokenType;
use ownHackathon\Core\Exception\HttpInvalidArgumentException;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Repository\TokenRepositoryInterface;
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
            return $this->errorResponse('No token was passed.', $token);
        }

        $persistedToken = $this->tokenRepository->findByToken($token);

        if (!($persistedToken instanceof TokenInterface) || $persistedToken->tokenType !== TokenType::EMail) {
            return $this->errorResponse('Invalid token was passed.', $token);
        }

        $account = $this->accountRepository->findById($persistedToken->accountId);

        if (!($account instanceof Account)) {
            return $this->errorResponse('Invalid token was passed.', $token);
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
            ResponseMessage::TOKEN_INVALID,
            [
                'Token:' => $token,
            ]
        );
    }
}
