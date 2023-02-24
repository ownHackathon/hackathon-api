<?php

namespace App\Middleware\Event;

use App\Enum\EventStatus;
use App\Entity\User;
use App\Service\EventService;
use App\Service\ParticipantService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventParticipantUnsubscribeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ParticipantService $participantService,
        private readonly EventService $eventService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $participantRemoveStatus = false;

        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::USER_ATTRIBUTE);
        $eventId = (int)$request->getAttribute('eventId');

        $event = $this->eventService->findById($eventId);

        if ($event->getStatus() >= EventStatus::RUNNING->value) {
            return $handler->handle($request->withAttribute('participantRemoveStatus', $participantRemoveStatus));
        }

        $participant = $this->participantService->findByUserIdAndEventId($user->getId(), $eventId);

        $participantRemoveStatus = $this->participantService->remove($participant);

        return $handler->handle($request->withAttribute('participantRemoveStatus', $participantRemoveStatus));
    }
}
