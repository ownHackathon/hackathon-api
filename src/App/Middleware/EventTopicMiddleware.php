<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Event;
use App\Model\Topic;
use App\Service\TopicPoolService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventTopicMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TopicPoolService $service,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $event = $request->getAttribute(Event::class);

        $topic = $this->service->findByEventId($event->getId());

        return $handler->handle($request->withAttribute(Topic::class, $topic));
    }
}
