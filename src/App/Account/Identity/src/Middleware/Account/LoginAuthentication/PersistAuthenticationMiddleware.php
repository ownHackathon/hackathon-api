<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Middleware\Account\LoginAuthentication;

use Exdrals\Account\Identity\Domain\AccountAccessAuth;
use Exdrals\Account\Identity\Domain\AccountInterface;
use Exdrals\Account\Identity\DTO\Client\ClientIdentification;
use Exdrals\Account\Identity\DTO\Token\RefreshToken;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\DuplicateEntryException;
use Shared\Domain\Exception\HttpDuplicateEntryException;
use Shared\Domain\Exception\HttpUnauthorizedException;

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
                LogMessage::AUTHENTICATION_PERSISTENCE_ERROR,
                StatusMessage::INVALID_DATA,
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
                LogMessage::DUPLICATE_SOURCE_LOGIN,
                StatusMessage::INVALID_DATA,
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
