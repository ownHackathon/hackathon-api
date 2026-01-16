<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\App\Entity\Account;
use ownHackathon\App\Enum\TokenType;
use ownHackathon\App\Service\Account\AccountService;
use ownHackathon\Core\Entity\TokenInterface;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Repository\TokenRepositoryInterface;

readonly class PasswordChangeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TokenRepositoryInterface $tokenRepository,
        private AccountService $accountService,
        private LoggerInterface $logger,
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

        if (!($persistedToken instanceof TokenInterface) || $persistedToken->getTokenType() !== TokenType::EMail) {
            return $this->errorResponse('Invalid token was passed.', $token);
        }

        $account = $this->accountRepository->findById($persistedToken->getAccountId());

        if (!($account instanceof Account)) {
            return $this->errorResponse('Invalid token was passed.', $token);
        }

        $hashedPassword = $this->accountService->cryptPassword($password);
        $account = $account->with(password: $hashedPassword);

        $this->accountRepository->update($account);
        $this->tokenRepository->deleteById($persistedToken->getId());

        return $handler->handle($request);
    }

    private function errorResponse(string $logMessage, ?string $token): ResponseInterface
    {
        $this->logger->notice($logMessage, [
            'Token:' => $token,
        ]);

        $message = HttpFailureMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::TOKEN_INVALID);

        return new JsonResponse($message, HTTP::STATUS_BAD_REQUEST);
    }
}
