<?php declare(strict_types=1);

namespace Core\Middleware;

use Core\Factory\ErrorResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
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
