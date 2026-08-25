<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account;

use Exdrals\Core\Shared\Domain\Exception\HttpUnauthorizedException;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function strlen;

readonly class RequestAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessTokenService $accessTokenService,
        private AccountRepositoryInterface $accountRepository,
        private UuidFactoryInterface $uuid,
        private LoggerInterface $logger,
    ) {
    }

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
                ]
            );
        }

        $authorization = $this->accessTokenService->decode($authorization);
        $uuid = $this->uuid->fromString($authorization->uuid);
        $account = $this->accountRepository->findOneByUuid($uuid);
        if (!($account instanceof AccountInterface)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::ACCESS_TOKEN_ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'uri' => (string)$request->getUri(),
                    'uuid' => $authorization->uuid,
                ],
                Level::Warning
            );
        }

        $this->logger->info('Authenticated user call.', [
            'Account' => $account->name,
            'uri' => (string)$request->getUri(),
        ]);

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
