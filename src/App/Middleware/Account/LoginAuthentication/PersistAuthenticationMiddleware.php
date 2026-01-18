<?php declare(strict_types=1);

namespace App\Middleware\Account\LoginAuthentication;

use DateTimeImmutable;
use App\DTO\Client\ClientIdentification;
use App\DTO\Token\RefreshToken;
use App\Entity\Account\AccountAccessAuth;
use Core\Entity\Account\AccountInterface;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\DuplicateEntryException;
use Core\Exception\HttpDuplicateEntryException;
use Core\Exception\HttpUnauthorizedException;
use Core\Repository\AccountAccessAuthRepositoryInterface;
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
