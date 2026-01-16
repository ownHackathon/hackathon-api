<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use DateTimeImmutable;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\App\DTO\RefreshToken;
use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Exception\DuplicateEntryException;
use ownHackathon\Core\Exception\HttpDuplicateEntryException;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Message\ResponseMessage;
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
                'Authentication could not be permanently stored due to missing data.',
                ResponseMessage::DATA_INVALID,
                [
                    // @phpstan-ignore-next-line
                    'Account:' => isset($account) ? $account->getEMail() : null,
                    // @phpstan-ignore-next-line
                    'Client ID:' => $clientIdent ? $clientIdent->identificationHash : null,
                    // @phpstan-ignore-next-line
                    'Refresh Token:' => $refreshToken ? 'placed' : null,
                ]
            );
        }

        $accountAccessAuth = new AccountAccessAuth(
            1,
            $account->getId(),
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
                'Login is not possible due to a duplicate login from the same source.',
                ResponseMessage::DATA_INVALID,
                [
                    'Account' => $account->getName(),
                    'ClientID' => $clientIdent->identificationHash,
                    'ErrorMessage' => $e->getMessage(),
                ],
            );
        }

        return $handler->handle($request);
    }
}
