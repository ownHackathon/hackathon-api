<?php declare(strict_types=1);

namespace App\Middleware\Token;

use Monolog\Level;
use App\Entity\Account\AccountAccessAuth;
use Core\Entity\Account\AccountAccessAuthInterface;
use Core\Entity\Account\AccountInterface;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpUnauthorizedException;
use Core\Repository\AccountRepositoryInterface;
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
