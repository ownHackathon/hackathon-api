<?php declare(strict_types=1);

namespace App\Handler\Event;

use App\Entity\User;
use App\Service\Participant\ParticipantService;
use App\Service\Project\ProjectService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventParticipantSubscribeHandler implements RequestHandlerInterface
{
    public function __construct(
        private ParticipantService $participantService,
        private ProjectService $projectService,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $participantCreateStatus = $request->getAttribute('participantCreateStatus');

        if (!$participantCreateStatus) {
            return new JsonResponse(
                ['Status' => 'Benutzer konnte der Teilnehmerliste nicht hinzugefügt werden'],
                HTTP::STATUS_METHOD_NOT_ALLOWED
            );
        }

        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::AUTHENTICATED_USER);
        $eventId = (int)$request->getAttribute('eventId');

        $participant = $this->participantService->findByUserIdAndEventId($user->id, $eventId);
        $project = $this->projectService->findByParticipantId($participant->id);

        $participantData = [
            'id' => $participant->id,
            'username' => $user->name,
            'userUuid' => $user->uuid,
            'requestedAt' => $participant->requestedAt->format('Y-m-d H:i'),
            'projectId' => $project?->id,
            'projectTitle' => $project?->title,
        ];

        return new JsonResponse($participantData, HTTP::STATUS_OK);
    }
}
