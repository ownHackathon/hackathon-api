<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use Monolog\Level;
use ownHackathon\App\DTO\ClientIdentification;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
                'Unauthorized access',
                'Unauthorized access',
                [],
                Level::Warning
            );
        }

        /** @var ClientIdentification $clientId */
        $clientId = $request->getAttribute(ClientIdentification::class);

        $accountAccessAuth = $this->authRepository->findByAccountIdAndClientIdHash(
            $account->getId(),
            $clientId->identificationHash
        );

        if (!($accountAccessAuth instanceof AccountAccessAuthInterface)) {
            throw new HttpUnauthorizedException(
                'Unauthorized access',
                'Unauthorized access',
                [
                    'accountId' => $account->getId(),
                    'clientIdentificationHash' => $clientId->identificationHash,
                ],
                Level::Warning
            );
        }

        $this->authRepository->deleteById($accountAccessAuth->getId());

        return $handler->handle($request);
    }
}
