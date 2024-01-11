<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Entity\Event;
use App\Service\Event\EventService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventService $eventService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $eventId = (int)$request->getAttribute('eventId');

        $event = $this->eventService->findById($eventId);

        return $handler->handle($request->withAttribute(Event::class, $event));
    }
}
