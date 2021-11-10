<?php declare(strict_types=1);

namespace App\Middleware;

use App\Service\TopicPoolService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TopicListMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TopicPoolService $service,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $topics = $this->service->findAll();

        return $handler->handle($request->withAttribute('topics', $topics));
    }
}
