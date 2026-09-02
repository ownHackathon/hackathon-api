<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Account;

use Monolog\Level;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use ownHackathon\Core\Http\Exception\HttpUnauthorizedException;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function strlen;

readonly final class RequestAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessTokenService $accessTokenService,
        private AccountRepositoryInterface $accountRepository,
        private UuidFactoryInterface $uuid,
        private LoggerInterface $logger,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authorization = $request->getHeaderLine('Authorization');

        if (strlen($authorization) === 0) {
            $this->logger->info('Guest call', [
                'uri' => (string)$request->getUri(),
            ]);

            return $handler->handle($request);
        }

        if (!$this->accessTokenService->isValid($authorization)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::ACCESS_TOKEN_EXPIRED,
                IdentityStatusMessage::TOKEN_EXPIRED,
                [
                    'uri' => (string)$request->getUri(),
                    'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
                ],
            );
        }

        $authorization = $this->accessTokenService->decode($authorization);
        try {
            $uuid = $this->uuid->fromString($authorization->uuid);
        } catch (\Throwable) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::ACCESS_TOKEN_ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'uuid' => $authorization->uuid ?? null,
                ],
                Level::Warning,
            );
        }
        $account = $this->accountRepository->findOneByUuid($uuid);
        if (!($account instanceof AccountInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::ACCESS_TOKEN_ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'uri' => (string)$request->getUri(),
                    'uuid' => $authorization->uuid,
                ],
                Level::Warning,
            );
        }

        $this->logger->info('Authenticated user call.', [
            'accountId' => $account->id,
            'accountUuid' => $account->uuid->toString(),
            'uri' => (string)$request->getUri(),
        ]);

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
