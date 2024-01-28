<?php declare(strict_types=1);

namespace App\Table;

use App\Entity\Topic;
use App\Repository\TopicPoolRepository;

readonly class TopicPoolTable extends AbstractTable implements TopicPoolRepository
{
    public function insert(Topic $topic): self
    {
        $values = [
            'uuid' => $topic->uuid->getHex()->toString(),
            'topic' => $topic->topic,
            'description' => $topic->description,
        ];

        $this->query->insertInto($this->table, $values)->execute();

        return $this;
    }

    public function findByUuId(string $uuid): bool|array
    {
        return $this->query->from($this->table)
            ->where('uuid', $uuid)
            ->fetch();
    }

    public function assignAnEvent(int $topicId, int $eventId): self
    {
        $values = [
            'eventId' => $eventId,
        ];
        $this->query->update($this->table, $values, $topicId)->execute();

        return $this;
    }

    public function findByEventId(int $eventId): bool|array
    {
        return $this->query->from($this->table)
            ->where('eventId', $eventId)
            ->fetch();
    }

    public function findAvailable(): bool|array
    {
        return $this->query->from($this->table)
            ->where('eventId', null)
            ->where('accepted', 1)
            ->fetchAll();
    }

    public function findByTopic(string $topic): bool|array
    {
        return $this->query->from($this->table)
            ->where('topic', $topic)
            ->fetch();
    }

    public function getCountTopic(): int
    {
        $data = $this->query->from($this->table)
            ->select('COUNT(id) AS countTopic')
            ->fetch();
        return $data['countTopic'];
    }

    public function getCountTopicAccepted(): int
    {
        $data = $this->query->from($this->table)
            ->select('COUNT(id) AS countTopic')
            ->where('accepted', 1)
            ->fetch();
        return $data['countTopic'];
    }

    public function getCountTopicSelectionAvailable(): int
    {
        $data = $this->query->from($this->table)
            ->select('COUNT(id) AS countTopic')
            ->where('accepted', 1)
            ->where('eventId', null)
            ->fetch();
        return $data['countTopic'];
    }
}
