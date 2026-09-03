<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Account;

use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use Monolog\Level;
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
        try {
            $account = $this->accountRepository->findOneByUuid($uuid);
        } catch (EmptyResultException) {
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
