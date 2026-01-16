<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountRepositoryInterface;

readonly class RefreshTokenAccountMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountAccessAuth $accountAccessAuth */
        $accountAccessAuth = $request->getAttribute(AccountAccessAuth::class);

        /** @var null|AccountInterface $account */
        $account = $this->accountRepository->findById($accountAccessAuth->getUserId());

        if ($account === null) {
            return new JsonResponse(
                HttpFailureMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::ACCOUNT_UNAUTHORIZED),
                HTTP::STATUS_UNAUTHORIZED
            );
        }
        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
