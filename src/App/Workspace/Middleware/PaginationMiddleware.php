<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Middleware;

use ownHackathon\Core\Persistence\Pagination;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class PaginationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();

        $pagination = Pagination::fromParams($params);

        return $handler->handle($request->withAttribute(Pagination::class, $pagination));
    }
}
