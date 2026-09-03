<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Account;

use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use Core\Http\Exception\HttpInvalidArgumentException;
use DateTimeImmutable;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class LastActivityUpdaterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);
        if (!($account instanceof AccountInterface)) {
            return $handler->handle($request);
        }

        try {
            $this->accountRepository->update(
                $account->with(lastActionAt: new DateTimeImmutable()),
            );
        } catch (InvalidArgumentException $exception) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::ACCOUNT_UPDATE_UNKNOWN_ERROR,
                IdentityStatusMessage::UNKNOWN_ERROR,
                [
                    'account' => $account->name,
                    'exception' => $exception,
                ],
            );
        }

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
