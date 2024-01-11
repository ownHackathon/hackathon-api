<?php declare(strict_types=1);

namespace App\Service\Topic;

use App\Entity\Topic;
use App\Exception\HttpException;
use App\Hydrator\ReflectionHydrator;
use App\Table\TopicPoolTable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use JetBrains\PhpStorm\ArrayShape;
use PDOException;
use Psr\Log\InvalidArgumentException;

class TopicPoolService
{
    public function __construct(
        private readonly TopicPoolTable $table,
        private readonly ReflectionHydrator $hydrator,
    ) {
    }

    /**
     * @throws HttpException
     */
    public function insert(Topic $topic): self
    {
        try {
            $this->table->insert($topic);
        } catch (PDOException $e) {
            /** TODO: Change to Logger */
            throw new HttpException(['PDO' => $e->getMessage()], HTTP::STATUS_INTERNAL_SERVER_ERROR);
        }

        return $this;
    }

    public function updateEventId(Topic $topic): self
    {
        $this->table->updateEventId($topic);

        return $this;
    }

    public function findById(int $id): Topic
    {
        $event = $this->table->findById($id);

        if ($event === []) {
            throw new InvalidArgumentException(
                sprintf('Could not find Event with id %d', $id),
                HTTP::STATUS_NOT_FOUND
            );
        }

        return $this->hydrator->hydrate($event, new Topic());
    }

    public function findByEventId(int $id): ?Topic
    {
        $topic = $this->table->findByEventId($id);

        return $this->hydrator->hydrate($topic, new Topic());
    }

    /**
     * @return array<Topic>|null
     */
    public function findAvailable(): ?array
    {
        $topics = $this->table->findAvailable();

        return $this->hydrator->hydrateList($topics, Topic::class);
    }

    /**
     * @return array<Topic>|null
     */
    public function findAll(): ?array
    {
        $topics = $this->table->findAll();

        return $this->hydrator->hydrateList($topics, Topic::class);
    }

    public function isTopic(string $topic): bool
    {
        $topic = $this->findByTopic($topic);

        return $topic instanceof Topic;
    }

    public function findByTopic(string $topic): ?Topic
    {
        $topic = $this->table->findByTopic($topic);

        return $this->hydrator->hydrate($topic, new Topic());
    }

    #[ArrayShape([
        'allTopic' => 'int',
        'allAcceptedTopic' => 'int',
        'allSelectionAvailableTopic' => 'int',
    ])]
    public function getEntriesStatistic(): array
    {
        return [
            'allTopic' => $this->table->getCountTopic(),
            'allAcceptedTopic' => $this->table->getCountTopicAccepted(),
            'allSelectionAvailableTopic' => $this->table->getCountTopicSelectionAvailable(),
        ];
    }
}
