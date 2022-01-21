<?php declare(strict_types=1);

namespace App\Middleware;

use App\Service\ParticipantService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventParticipantMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ParticipantService $participantService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $eventId = (int)$request->getAttribute('eventId');

        $participants = $this->participantService->findActiveParticipantByEvent($eventId);

        return $handler->handle($request->withAttribute('participants', $participants));
    }
}
