<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use DateTimeImmutable;
use ownHackathon\App\DTO\Client\ClientIdentification;
use ownHackathon\App\DTO\Token\RefreshToken;
use ownHackathon\App\Entity\Account\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Enum\Message\LogMessage;
use ownHackathon\Core\Enum\Message\StatusMessage;
use ownHackathon\Core\Exception\DuplicateEntryException;
use ownHackathon\Core\Exception\HttpDuplicateEntryException;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
                    'Account:' => isset($account) ? $account->email : null,
                    // @phpstan-ignore-next-line
                    'Client ID:' => $clientIdent ? $clientIdent->identificationHash : null,
                    'Refresh Token:' => $refreshToken ? 'placed' : null,
                ]
            );
        }

        $accountAccessAuth = new AccountAccessAuth(
            1,
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
