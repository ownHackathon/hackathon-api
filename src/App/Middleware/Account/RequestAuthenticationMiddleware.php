<?php declare(strict_types=1);

namespace App\Middleware\Account;

use App\Service\Token\AccessTokenService;
use Core\Entity\Account\AccountInterface;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpUnauthorizedException;
use Core\Repository\Account\AccountRepositoryInterface;
use Core\Utils\UuidFactoryInterface;
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
                LogMessage::ACCESS_TOKEN_EXPIRED,
                StatusMessage::TOKEN_EXPIRED,
                [
                    'uri' => (string)$request->getUri(),
                    'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
                ]
            );
        }

        $authorization = $this->accessTokenService->decode($authorization);
        $uuid = $this->uuid->fromString($authorization->uuid);
        $account = $this->accountRepository->findByUuid($uuid);
        if (!($account instanceof AccountInterface)) {
            throw new HttpUnauthorizedException(
                LogMessage::ACCESS_TOKEN_ACCOUNT_NOT_FOUND,
                StatusMessage::TOKEN_INVALID,
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
