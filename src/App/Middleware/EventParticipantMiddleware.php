<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Participant;
use App\Service\ParticipantService;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventParticipantMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ParticipantService $participantService,
        private UserService $userService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $eventId = (int)$request->getAttribute('eventId');

        $participants = $this->participantService->findActiveParticipantByEvent($eventId);
        $eventParticipants = [];

        /** @var Participant $participant */
        foreach ($participants as $participant) {
            $eventParticipants [] = $this->userService->findById($participant->getUserId());
        }

        return $handler->handle($request->withAttribute('participants', $eventParticipants));
    }
}