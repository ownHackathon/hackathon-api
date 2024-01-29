<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Entity\Participant;
use App\Entity\User;
use App\Enum\EventStatus;
use App\Service\Event\EventService;
use App\Service\Participant\ParticipantService;
use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventParticipantSubscribeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ParticipantService $participantService,
        private EventService $eventService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $eventId = (int)$request->getAttribute('eventId');
        $event = $this->eventService->findById($eventId);

        if ($event->status->value >= EventStatus::RUNNING->value) {
            return $handler->handle($request->withAttribute('participantCreateStatus', false));
        }

        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::AUTHENTICATED_USER);

        $participant = new Participant(
            1,
            $user->id,
            $eventId,
            new DateTime(),
            true,
            false
        );
        $participantCreateStatus = $this->participantService->create($participant);

        return $handler->handle($request->withAttribute('participantCreateStatus', $participantCreateStatus));
    }
}
