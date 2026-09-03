<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Account;

use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\DTO\Client\ClientIdentification;
use Core\Http\Middleware\RequestCorrelationMiddleware;
use Core\Observability\IpMasker;
use Core\Observability\UserAgentSummarizer;
use Fig\Http\Message\StatusCodeInterface as Http;
use Mezzio\Router\RouteResult;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function hrtime;
use function sprintf;

readonly final class AccountActivityLoggingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $startedAt = hrtime(true);

        $response = $handler->handle($request);

        $durationSeconds = (hrtime(true) - $startedAt) / 1_000_000_000;
        $statusCode = $response->getStatusCode();

        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);
        $client = $request->getAttribute(ClientIdentification::class);
        $routeResult = $request->getAttribute(RouteResult::class);

        $context = [
            'accountId' => $account instanceof AccountInterface ? $account->id : null,
            'accountUuid' => $account instanceof AccountInterface ? $account->uuid->toString() : null,
            'guest' => !($account instanceof AccountInterface),
            'route' => $routeResult instanceof RouteResult ? $routeResult->getMatchedRouteName() : null,
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'status' => $statusCode,
            'duration' => sprintf('%.3fs', $durationSeconds),
            'ip' => IpMasker::mask($request->getServerParams()['REMOTE_ADDR'] ?? null),
            'userAgent' => $client instanceof ClientIdentification
                ? UserAgentSummarizer::summarize($client->clientIdentificationData->userAgent)
                : UserAgentSummarizer::summarize($request->getHeaderLine('user-agent')),
            'clientIdentHash' => $client instanceof ClientIdentification ? $client->identificationHash : null,
            'correlation_id' => $request->getAttribute(RequestCorrelationMiddleware::CORRELATION_ID),
        ];

        $message = match (true) {
            $statusCode >= Http::STATUS_INTERNAL_SERVER_ERROR => IdentityLogMessage::ACTIVITY_INTERACTION_ERROR,
            $statusCode >= Http::STATUS_BAD_REQUEST => IdentityLogMessage::ACTIVITY_INTERACTION_WARNING,
            default => IdentityLogMessage::ACTIVITY_INTERACTION,
        };

        $this->logger->log(
            $this->levelForStatus($statusCode)->value,
            $message,
            $context,
        );

        return $response;
    }

    private function levelForStatus(int $statusCode): Level
    {
        return match (true) {
            $statusCode >= Http::STATUS_INTERNAL_SERVER_ERROR => Level::Error,
            $statusCode >= Http::STATUS_BAD_REQUEST => Level::Warning,
            default => Level::Info,
        };
    }
}
