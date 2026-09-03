<?php declare(strict_types=1);

namespace Core\Http\Middleware;

use Fig\Http\Message\StatusCodeInterface as Http;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function hrtime;
use function sprintf;

final readonly class RequestLoggingMiddleware implements MiddlewareInterface
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

        $context = [
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'status' => $statusCode,
            'duration' => sprintf('%.3fs', $durationSeconds),
            'correlation_id' => $request->getAttribute(RequestCorrelationMiddleware::CORRELATION_ID),
        ];

        $this->logger->log(
            $this->levelForStatus($statusCode)->value,
            sprintf('[%d] %s %s', $statusCode, $request->getMethod(), (string) $request->getUri()),
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
