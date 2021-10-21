<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Participant;
use App\Service\EventService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProjectEventRatingReleasedMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventService $eventService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Participant $participant */
        $participant = $request->getAttribute(Participant::class);

        $isRatingReleased = $this->eventService->isRatingCompleted($participant->getEventId());

        return $handler->handle($request->withAttribute('isRatingReleased', $isRatingReleased));
    }
}
