<?php declare(strict_types=1);

namespace Exdrals\Shared\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exdrals\Shared\Infrastructure\Factory\ErrorResponseFactory;
use Throwable;

final readonly class ApiErrorHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ErrorResponseFactory $errorResponseFactory,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->errorResponseFactory->createFromThrowable($e);
        }
    }
}
