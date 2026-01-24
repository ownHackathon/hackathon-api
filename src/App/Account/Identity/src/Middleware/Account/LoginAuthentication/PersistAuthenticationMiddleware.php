<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account\LoginAuthentication;

use DateTimeImmutable;
use Exdrals\Identity\Domain\AccountAccessAuth;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Token\RefreshToken;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Shared\Domain\Exception\HttpDuplicateEntryException;
use Exdrals\Shared\Domain\Exception\HttpUnauthorizedException;

readonly class PersistAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountAccessAuthRepositoryInterface $repository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AccountInterface $account */
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        /** @var ClientIdentification $clientIdent */
        $clientIdent = $request->getAttribute(ClientIdentification::class);

        /** @var RefreshToken $refreshToken */
        $refreshToken = $request->getAttribute(RefreshToken::class);

        // @phpstan-ignore-next-line
        if ($account === null || $clientIdent === null || $refreshToken === null) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::AUTHENTICATION_PERSISTENCE_ERROR,
                IdentityStatusMessage::INVALID_DATA,
                [
                    // @phpstan-ignore-next-line
                    'Account:' => $account?->email,
                    // @phpstan-ignore-next-line
                    'Client ID:' => $clientIdent?->identificationHash,
                    'Refresh Token:' => $refreshToken ? 'placed' : null,
                ]
            );
        }

        $accountAccessAuth = new AccountAccessAuth(
            null,
            $account->id,
            'default',
            $refreshToken->refreshToken,
            $clientIdent->clientIdentificationData->userAgent,
            $clientIdent->identificationHash,
            new DateTimeImmutable()
        );
        try {
            $this->repository->insert($accountAccessAuth);
        } catch (DuplicateEntryException $e) {
            throw new HttpDuplicateEntryException(
                IdentityLogMessage::DUPLICATE_SOURCE_LOGIN,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'Account' => $account->name,
                    'ClientID' => $clientIdent->identificationHash,
                    'ErrorMessage' => $e->getMessage(),
                ],
            );
        }

        return $handler->handle($request);
    }
}
