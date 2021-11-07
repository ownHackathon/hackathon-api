<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Topic;
use App\Table\TopicPoolTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Log\InvalidArgumentException;

class TopicPoolService
{
    use ConvertArrayToClassArrayTrait;

    public function __construct(
        private TopicPoolTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
    }

    public function insert(Topic $topic): self
    {
        $this->table->insert($topic);

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

    public function findAll(): ?array
    {
        $events = $this->table->findAll();

        if (!$events) {
            return null;
        }

        return $this->convertArrayToClassArray($events, Topic::class);
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

        if (!$topic) {
            return null;
        }

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
