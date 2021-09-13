<?php declare(strict_types=1);

namespace App\Middleware;

use App\Service\EventListService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventListMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventListService $eventListService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $allActiveEvents = $this->eventListService->findAllActive();
        $allNotActiveEvents = $this->eventListService->findAllNotActive();

        return $handler->handle(
            $request->withAttribute('ActiveEvents', $allActiveEvents)
                ->withAttribute('NotActiveEvents', $allNotActiveEvents)
        );
    }
}
