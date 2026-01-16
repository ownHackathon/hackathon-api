<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

readonly class RefreshTokenAccountMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountAccessAuth $accountAccessAuth */
        $accountAccessAuth = $request->getAttribute(AccountAccessAuthInterface::class);

        /** @var null|AccountInterface $account */
        $account = $this->accountRepository->findById($accountAccessAuth->getAccountId());

        if ($account === null) {
            $this->logger->warning('Account does not exist in the transmitted token', [
                'AccessAuth ID:' => $accountAccessAuth->getId(),
                'Account ID:' => $accountAccessAuth->getAccountId(),
            ]);

            return new JsonResponse(
                HttpResponseMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::ACCOUNT_UNAUTHORIZED),
                HTTP::STATUS_UNAUTHORIZED
            );
        }
        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
