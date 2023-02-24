<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Entity\Event;
use App\Service\EventService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\InvalidArgumentException;

class EventNameMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly EventService $eventService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $eventName = $request->getAttribute('eventName');

        $event = $this->eventService->findByTitle($eventName);

        if (!$event instanceof Event) {
            throw new InvalidArgumentException('Could not find Event', 400);
        }

        return $handler->handle($request->withAttribute(Event::class, $event));
    }
}
