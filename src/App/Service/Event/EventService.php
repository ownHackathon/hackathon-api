<?php declare(strict_types=1);

namespace App\Service\Event;

use App\Entity\Event;
use App\Hydrator\ReflectionHydrator;
use App\Repository\EventRepository;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Psr\Log\InvalidArgumentException;

class EventService
{
    public function __construct(
        private readonly EventRepository $repository,
        private readonly ReflectionHydrator $hydrator,
    ) {
    }

    public function create(Event $event): bool
    {
        if ($this->isEventExist($event->title)) {
            return false;
        }

        $this->repository->insert($event);

        return true;
    }

    public function findById(int $id): Event
    {
        $event = $this->repository->findById($id);

        if ($event === []) {
            throw new InvalidArgumentException(
                sprintf('Could not find Event with id %d', $id),
                HTTP::STATUS_NOT_FOUND
            );
        }

        return $this->hydrator->hydrate($event, Event::class);
    }

    public function findByTitle(string $topic): ?Event
    {
        $event = $this->repository->findByTitle($topic);

        return $this->hydrator->hydrate($event, Event::class);
    }

    /**
     * @return array<Event>
     */
    public function findAll(string $order = 'startedAt', string $sort = 'DESC'): array
    {
        $events = $this->repository->findAll($order, $sort);

        return $this->hydrator->hydrateList($events, Event::class);
    }

    /**
     * @return array<Event>|null
     */
    public function findAllActive(): ?array
    {
        $events = $this->repository->findAllActive();

        return $this->hydrator->hydrateList($events, Event::class);
    }

    /**
     * @return array<Event>|null
     */
    public function findAllNotActive(): ?array
    {
        $events = $this->repository->findAllInactive();

        return $this->hydrator->hydrateList($events, Event::class);
    }

    public function isRatingCompleted(int $id): bool
    {
        $event = $this->findById($id);

        return $event->ratingCompleted;
    }

    public function isEventExist(string $topic): bool
    {
        $event = $this->findByTitle($topic);

        return $event instanceof Event;
    }
}
