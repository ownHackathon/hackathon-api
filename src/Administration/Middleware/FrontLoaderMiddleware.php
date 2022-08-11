<?php declare(strict_types=1);

namespace Administration\Middleware;

use App\Handler\IndexHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_key_exists;

class FrontLoaderMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly IndexHandler $indexHandler
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaders();

        if (!array_key_exists('x-frontloader', $header)) {
            return $this->indexHandler->handle($request);
        }

        return $handler->handle($request);
    }
}
