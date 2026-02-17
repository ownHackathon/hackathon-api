<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account;

use DateTimeImmutable;
use Exdrals\Core\Shared\Domain\Exception\HttpInvalidArgumentException;
use InvalidArgumentException;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class LastActivityUpdaterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);
        if (!($account instanceof AccountInterface)) {
            return $handler->handle($request);
        }

        try {
            $this->accountRepository->update(
                $account->with(lastActionAt: new DateTimeImmutable())
            );
        } catch (InvalidArgumentException $exception) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACCOUNT_UPDATE_UNKNOWN_ERROR,
                IdentityStatusMessage::UNKNOWN_ERROR,
                [
                    'account' => $account->name,
                    'exception' => $exception,
                ]
            );
        }

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
