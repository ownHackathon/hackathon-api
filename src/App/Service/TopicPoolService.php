<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Topic;
use App\Table\TopicPoolTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use App\Hydrator\ReflectionHydrator;
use Psr\Log\InvalidArgumentException;

class TopicPoolService
{
    use ConvertArrayToClassArrayTrait;

    public function __construct(
        private TopicPoolTable $table,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function insert(Topic $topic): self
    {
        $this->table->insert($topic);

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

        if (!$event) {
            throw new InvalidArgumentException('Could not find Event', 400);
        }

        return $this->hydrator->hydrate($event, new Topic());
    }

    public function findByEventId(int $id): ?Topic
    {
        $topic = $this->table->findByEventId($id);

        return $this->hydrator->hydrate($topic, new Topic());
    }

    public function findAvailable(): ?array
    {
        $topics = $this->table->findAvailable();

        return $this->convertArrayToClassArray($topics, Topic::class);
    }

    public function findAll(): ?array
    {
        $topics = $this->table->findAll();

        return $this->convertArrayToClassArray($topics, Topic::class);
    }

    public function isTopic(string $topic): bool
    {
        if (null === $this->findByTopic($topic)) {
            return false;
        }

        return true;
    }

    public function findByTopic(string $topic): ?Topic
    {
        $topic = $this->table->findByTopic($topic);

        return $this->hydrator->hydrate($topic, new Topic());
    }

    public function getEntriesStatistic(): array
    {
        return [
            'allTopic' => $this->table->getCountTopic(),
            'allAcceptedTopic' => $this->table->getCountTopicAccepted(),
            'allSelectionAvailableTopic' => $this->table->getCountTopicSelectionAvailable(),
        ];
    }
}
