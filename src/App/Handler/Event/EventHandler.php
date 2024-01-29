<?php declare(strict_types=1);

namespace App\Handler\Event;

use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Topic;
use App\Entity\User;
use App\Service\Participant\ParticipantService;
use App\Service\Project\ProjectService;
use App\Service\Topic\TopicPoolService;
use App\Service\User\UserService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventHandler implements RequestHandlerInterface
{
    public function __construct(
        private UserService $userService,
        private ParticipantService $participantService,
        private ProjectService $projectService,
        private TopicPoolService $topicPoolService,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var User $user
         */
        $user = $request->getAttribute(User::AUTHENTICATED_USER);

        /**
         * @var Event $event
         */
        $event = $request->getAttribute(Event::class);

        /**
         * @var array<Participant> $participants
         */
        $participants = $this->participantService->findActiveParticipantByEvent($event->id);

        $data = [
            'id' => $event->id,
            'owner' => $this->userService->findById($event->userId)->name,
            'title' => $event->title,
            'description' => $event->description,
            'eventText' => $event->eventText,
            'createdAt' => $event->createdAt->format('Y-m-d H:i'),
            'startedAt' => $event->startedAt->format('Y-m-d H:i'),
            'duration' => $event->duration,
            'status' => $event->status,
            'ratingCompleted' => $event->ratingCompleted,
        ];

        if ($user instanceof User) {
            $participantData = [];
            foreach ($participants as $participant) {
                $user = $this->userService->findById($participant->userId);
                $project = $this->projectService->findByParticipantId($participant->id);
                $entry = [
                    'id' => $participant->id,
                    'username' => $user->name,
                    'userUuid' => $user->uuid,
                    'requestedAt' => $participant->requestedAt->format('Y-m-d H:i'),
                    'projectId' => $project?->id,
                    'projectTitle' => $project?->title,
                ];

                $participantData[] = $entry;
            }

            $data['participants'] = $participantData;
        }

        $topic = $this->topicPoolService->findByEventId($event->id);

        if ($topic instanceof Topic) {
            $topicData = [
                'title' => $topic->topic,
                'description' => $topic->description,
            ];

            $data['topic'] = $topicData;
        }

        return new JsonResponse($data, HTTP::STATUS_OK);
    }
}
