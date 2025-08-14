<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\App\Service\Token\AccessTokenService;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Utils\UuidFactoryInterface;
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
            $this->logger->info('Guest call');

            return $handler->handle($request);
        }

        if (!$this->accessTokenService->isValid($authorization)) {
            $this->logger->notice('Request with expired Access Token received.', [
                'uri' => (string)$request->getUri(),
                'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
            ]);

            $message = HttpFailureMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::TOKEN_EXPIRED);

            return new JsonResponse($message, $message->statusCode);
        }

        $authorization = $this->accessTokenService->decode($authorization);
        $uuid = $this->uuid->fromString($authorization->uuid);
        $account = $this->accountRepository->findByUuid($uuid);
        if (!($account instanceof AccountInterface)) {
            $this->logger->notice('Request with invalid Access Token received (Account not found).', [
                'uri' => (string)$request->getUri(),
                'uuid' => $authorization->uuid,
            ]);

            $message = HttpFailureMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::TOKEN_INVALID);

            return new JsonResponse($message, $message->statusCode);
        }

        $this->logger->info('Authenticated user call.', [
            'Account' => $account->getName(),
            'uri' => (string)$request->getUri(),
        ]);

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
