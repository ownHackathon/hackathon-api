<?php declare(strict_types=1);

namespace Exdrals\Shared\Middleware;

use Envms\FluentPDO\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly class FluentTransactionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Query $fluentPdo
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
            return $handler->handle($request);
        }

        $pdo = $this->fluentPdo->getPdo();

        if ($pdo->inTransaction()) {
            return $handler->handle($request);
        }

        $pdo->beginTransaction();

        try {
            $response = $handler->handle($request);

            $pdo->commit();

            return $response;
        } catch (Throwable $exception) {
            // @phpstan-ignore-next-line
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }
}
