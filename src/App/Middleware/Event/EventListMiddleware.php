<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Dto\Event\EventDto;
use App\Dto\Event\EventListDto;
use App\Entity\Event;
use App\Service\Event\EventService;
use App\Service\User\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function strtoupper;

readonly class EventListMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventService $eventService,
        private UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();

        $sort = match (strtoupper($params['sort'] ?? '')) {
            'ASC' => 'ASC',
            default => 'DESC',
        };

        $order = match (strtoupper($params['order'] ?? '')) {
            'ID' => 'id',
            'OWNER' => 'owner',
            'TITLE' => 'title',
            'DESCRIPTION' => 'description',
            'DURATION' => 'duration',
            'STATUS' => 'status',
            default => 'startedAt',
        };

        /** @var array<Event> $events */
        $events = $this->eventService->findAll($order, $sort);

        $eventList = [];

        foreach ($events as $event) {
            $entry = new EventDto(
                $event->id,
                $this->userService->findById($event->userId)->name,
                $event->title,
                $event->description,
                $event->duration,
                $event->createdAt->format('Y-m-d H:i'),
                $event->status,
            );

            $eventList[] = $entry;
        }

        $eventList = new EventListDto($eventList);

        return $handler->handle($request->withAttribute(EventListDto::class, $eventList));
    }
}
