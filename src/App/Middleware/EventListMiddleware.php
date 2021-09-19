<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Service\EventService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventListMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventService $eventService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $allActiveEvents = $this->eventService->findAllActive();
        $allNotActiveEvents = $this->eventService->findAllNotActive();

        return $handler->handle(
            $request->withAttribute('ActiveEvents', $allActiveEvents)
                ->withAttribute('NotActiveEvents', $allNotActiveEvents)
        );
    }
}
