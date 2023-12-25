<?php declare(strict_types=1);

namespace App\Handler;

use App\Dto\EventDto;
use App\Entity\Event;
use App\Service\UserService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EventListHandler implements RequestHandlerInterface
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    #[OA\Get(path: '/api/event', tags: ['Event'])]
    #[OA\QueryParameter(
        name: 'sort',
        description: 'determines the display order of the events',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string'),
        example: 'asc'
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Success',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: EventDto::class))
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var array<Event> $events
         */
        $events = $request->getAttribute('events') ?? [];

        $data = [];

        foreach ($events as $event) {
            $entry = new EventDto([
                'id' => $event->getId(),
                'owner' => $this->userService->findById($event->getUserId())->getName(),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'duration' => $event->getDuration(),
                'startTime' => $event->getStartTime()->format('Y-m-d H:i'),
                'status' => $event->getStatus(),
            ]);

            $data[] = $entry;
        }

        return new JsonResponse($data, HTTP::STATUS_OK);
    }
}
