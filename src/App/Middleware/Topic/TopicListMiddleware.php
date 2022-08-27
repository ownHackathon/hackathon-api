<?php declare(strict_types=1);

namespace App\Middleware\Topic;

use App\Service\TopicPoolService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TopicListMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly TopicPoolService $topicPoolService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $topics = $this->topicPoolService->findAll();

        return $handler->handle($request->withAttribute('topics', $topics));
    }
}