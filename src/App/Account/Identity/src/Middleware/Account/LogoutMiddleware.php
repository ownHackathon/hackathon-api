<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account;

use Exdrals\Identity\Domain\AccountAccessAuthInterface;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;

readonly class LogoutMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountAccessAuthRepositoryInterface $authRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountInterface|null $account */
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        if (!($account instanceof AccountInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::LOGOUT_REQUIRES_AUTHENTICATION,
                IdentityStatusMessage::UNAUTHORIZED_ACCESS,
                [],
                Level::Warning
            );
        }

        /** @var ClientIdentification $clientId */
        $clientId = $request->getAttribute(ClientIdentification::class);

        $accountAccessAuth = $this->authRepository->findByAccountIdAndClientIdHash(
            $account->id,
            $clientId->identificationHash
        );

        if (!($accountAccessAuth instanceof AccountAccessAuthInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::LOGOUT_CLIENT_IDENTITY_MISMATCH,
                IdentityStatusMessage::UNAUTHORIZED_ACCESS,
                [
                    'accountId' => $account->id,
                    'clientIdentificationHash' => $clientId->identificationHash,
                ],
                Level::Warning
            );
        }

        $this->authRepository->deleteById($accountAccessAuth->id);

        return $handler->handle($request);
    }
}
