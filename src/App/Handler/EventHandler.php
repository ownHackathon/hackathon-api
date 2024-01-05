<?php declare(strict_types=1);

namespace App\Handler;

use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Topic;
use App\Entity\User;
use App\Service\ParticipantService;
use App\Service\ProjectService;
use App\Service\TopicPoolService;
use App\Service\UserService;
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
        $participants = $this->participantService->findActiveParticipantByEvent($event->getId());

        $data = [
            'id' => $event->getId(),
            'owner' => $this->userService->findById($event->getUserId())->getName(),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'eventText' => $event->getEventText(),
            'createTime' => $event->getCreateTime()->format('Y-m-d H:i'),
            'startTime' => $event->getStartTime()->format('Y-m-d H:i'),
            'duration' => $event->getDuration(),
            'status' => $event->getStatus(),
            'ratingCompleted' => $event->isRatingCompleted(),
        ];

        if ($user instanceof User) {
            $participantData = [];
            foreach ($participants as $participant) {
                $user = $this->userService->findById($participant->getUserId());
                $project = $this->projectService->findByParticipantId($participant->getId());
                $entry = [
                    'id' => $participant->getId(),
                    'username' => $user->getName(),
                    'userUuid' => $user->getUuid(),
                    'requestTime' => $participant->getRequestTime()->format('Y-m-d H:i'),
                    'projectId' => $project ? $project->getId() : '',
                    'projectTitle' => $project ? $project->getTitle() : '',
                ];

                $participantData[] = $entry;
            }

            $data['participants'] = $participantData;
        }

        $topic = $this->topicPoolService->findByEventId($event->getId());

        if ($topic instanceof Topic) {
            $topicData = [
                'title' => $topic->getTopic(),
                'description' => $topic->getDescription(),
            ];

            $data['topic'] = $topicData;
        }

        return new JsonResponse($data, HTTP::STATUS_OK);
    }
}
