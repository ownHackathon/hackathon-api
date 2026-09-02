<?php declare(strict_types=1);

namespace ownHackathon\Core\Http\Middleware;

use ownHackathon\Core\Observability\CorrelationIdRegistry;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function trim;

final class RequestCorrelationMiddleware implements MiddlewareInterface
{
    public const string CORRELATION_ID = 'correlation_id';
    public const string HEADER = 'X-Correlation-ID';

    public function __construct(
        private UuidFactoryInterface $uuidFactory,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $correlationId = trim($request->getHeaderLine(self::HEADER));
        if ($correlationId === '') {
            $correlationId = $this->uuidFactory->uuid4()->toString();
        }

        CorrelationIdRegistry::set($correlationId);
        $request = $request->withAttribute(self::CORRELATION_ID, $correlationId);

        try {
            return $handler->handle($request);
        } finally {
            CorrelationIdRegistry::clear();
        }
    }
}
