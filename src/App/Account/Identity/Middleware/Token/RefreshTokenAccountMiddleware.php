<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Token;

use Exdrals\Core\Shared\Domain\Exception\HttpUnauthorizedException;
use Exdrals\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class RefreshTokenAccountMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accountAccessAuth = $request->getAttribute(AccountAccessAuthInterface::class);
        $account = $this->accountRepository->findOneById($accountAccessAuth->accountId);

        if (!$account instanceof AccountAccessAuthInterface) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'AccessAuth ID:' => $accountAccessAuth->id,
                    'Account ID:' => $accountAccessAuth->accountId,
                ],
                Level::Warning
            );
        }

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
