<?php declare(strict_types=1);

namespace App\Service\Topic;

use App\Entity\Topic;
use App\Exception\HttpException;
use App\Hydrator\ReflectionHydrator;
use App\Repository\TopicPoolRepository;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use PDOException;

readonly class TopicPoolService
{
    public function __construct(
        private TopicPoolRepository $repository,
        private ReflectionHydrator $hydrator,
    ) {
    }

    /**
     * @throws HttpException
     */
    public function insert(Topic $topic): self
    {
        try {
            $this->repository->insert($topic);
        } catch (PDOException $e) {
            /** TODO: Change to Logger */
            throw new HttpException(['PDO' => $e->getMessage()], HTTP::STATUS_INTERNAL_SERVER_ERROR);
        }

        return $this;
    }

    public function updateEventId(Topic $topic): self
    {
        $this->repository->assignAnEvent($topic->id, $topic->eventId);

        return $this;
    }

    public function findById(int $id): Topic
    {
        $event = $this->repository->findById($id);

        if ($event === []) {
            throw new InvalidArgumentException(
                sprintf('Could not find Event with id %d', $id),
                HTTP::STATUS_NOT_FOUND
            );
        }

        return $this->hydrator->hydrate($event, Topic::class);
    }

    public function findByEventId(int $id): ?Topic
    {
        $topic = $this->repository->findByEventId($id);

        return $this->hydrator->hydrate($topic, Topic::class);
    }

    /**
     * @return array<Topic>|null
     */
    public function findAvailable(): ?array
    {
        $topics = $this->repository->findAvailable();

        return $this->hydrator->hydrateList($topics, Topic::class);
    }

    /**
     * @return array<Topic>|null
     */
    public function findAll(): ?array
    {
        $topics = $this->repository->findAll();

        return $this->hydrator->hydrateList($topics, Topic::class);
    }

    public function isTopic(string $topic): bool
    {
        $topic = $this->findByTopic($topic);

        return $topic instanceof Topic;
    }

    public function findByTopic(string $topic): ?Topic
    {
        $topic = $this->repository->findByTopic($topic);

        return $this->hydrator->hydrate($topic, Topic::class);
    }

    #[ArrayShape([
        'allTopic' => 'int',
        'allAcceptedTopic' => 'int',
        'allSelectionAvailableTopic' => 'int',
    ])]
    public function getEntriesStatistic(): array
    {
        return [
            'allTopic' => $this->repository->getCountTopic(),
            'allAcceptedTopic' => $this->repository->getCountTopicAccepted(),
            'allSelectionAvailableTopic' => $this->repository->getCountTopicSelectionAvailable(),
        ];
    }
}
