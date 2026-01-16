<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Token;

use Monolog\Level;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
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
        $account = $this->accountRepository->findById($accountAccessAuth->getAccountId());

        if ($account === null) {
            throw new HttpUnauthorizedException(
                'Account does not exist in the transmitted token',
                ResponseMessage::TOKEN_INVALID,
                [
                    'AccessAuth ID:' => $accountAccessAuth->getId(),
                    'Account ID:' => $accountAccessAuth->getAccountId(),
                ],
                Level::Warning
            );
        }
        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
