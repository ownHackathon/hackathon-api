<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Token;

use App\Account\Identity\Domain\AccountAccessAuthInterface;
use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class RefreshTokenAccountMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accountAccessAuth = $request->getAttribute(AccountAccessAuthInterface::class);
        if (!$accountAccessAuth instanceof AccountAccessAuthInterface) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::TOKEN_INVALID,
                [],
                Level::Warning,
            );
        }

        try {
            $account = $this->accountRepository->findOneById($accountAccessAuth->accountId);
        } catch (EmptyResultException) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'AccessAuth ID:' => $accountAccessAuth->id,
                    'Account ID:' => $accountAccessAuth->accountId,
                ],
                Level::Warning,
            );
        }

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
