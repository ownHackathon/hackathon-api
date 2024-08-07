<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Enum\EventStatus;
use App\Service\Event\EventService;
use App\Service\Participant\ParticipantService;
use Core\Entity\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventParticipantUnsubscribeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ParticipantService $participantService,
        private EventService $eventService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::AUTHENTICATED_USER);
        $eventId = (int)$request->getAttribute('eventId');

        $event = $this->eventService->findById($eventId);

        if ($event->status->value >= EventStatus::RUNNING->value) {
            return $handler->handle($request->withAttribute('participantRemoveStatus', false));
        }

        $participant = $this->participantService->findByUserIdAndEventId($user->id, $eventId);

        $participantRemoveStatus = $this->participantService->remove($participant);

        return $handler->handle($request->withAttribute('participantRemoveStatus', $participantRemoveStatus));
    }
}
