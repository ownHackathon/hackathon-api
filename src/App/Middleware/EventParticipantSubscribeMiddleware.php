<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Participant;
use App\Model\User;
use App\Service\ParticipantService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventParticipantSubscribeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ParticipantService $service,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $eventId = (int)$request->getAttribute('eventId');

        /** @var User $user */
        $user = $request->getAttribute(User::USER_ATTRIBUTE);

        $participant = new Participant();
        $participant->setUserId($user->getId())
            ->setEventId($eventId)
            ->setApproved(true);
        $this->service->create($participant);

        return $handler->handle($request);
    }
}
