<?php declare(strict_types=1);

namespace App\Middleware\Account;

use App\DTO\Client\ClientIdentification;
use Core\Entity\Account\AccountAccessAuthInterface;
use Core\Entity\Account\AccountInterface;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpUnauthorizedException;
use Core\Repository\Account\AccountAccessAuthRepositoryInterface;
use Monolog\Level;
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
                LogMessage::LOGOUT_REQUIRES_AUTHENTICATION,
                StatusMessage::UNAUTHORIZED_ACCESS,
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
                LogMessage::LOGOUT_CLIENT_IDENTITY_MISMATCH,
                StatusMessage::UNAUTHORIZED_ACCESS,
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
