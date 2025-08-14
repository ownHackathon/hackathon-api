<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use DateTimeImmutable;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class LastAktivityUpdaterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountInterface | null $account */
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        if (!($account instanceof AccountInterface)) {
            return $handler->handle($request);
        }

        $account = $account->with(lastActionAt: new DateTimeImmutable());

        $this->accountRepository->update($account);

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
