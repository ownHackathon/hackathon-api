<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Token;

use Monolog\Level;
use ownHackathon\App\Account\Identity\Domain\AccountAccessAuthInterface;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Shared\Domain\Exception\HttpUnauthorizedException;
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
