<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Middleware\Token;

use Exdrals\Account\Identity\Domain\AccountAccessAuth;
use Exdrals\Account\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Account\Identity\Domain\AccountInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpUnauthorizedException;

readonly class RefreshTokenAccountMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountAccessAuth $accountAccessAuth */
        $accountAccessAuth = $request->getAttribute(AccountAccessAuthInterface::class);

        /** @var null|AccountInterface $account */
        $account = $this->accountRepository->findById($accountAccessAuth->accountId);

        if ($account === null) {
            throw new HttpUnauthorizedException(
                LogMessage::REFRESH_TOKEN_ACCOUNT_NOT_FOUND,
                StatusMessage::TOKEN_INVALID,
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
