<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use App\Model\Topic;

class TopicPoolTable extends AbstractTable
{
    public function insert(Topic $topic): self
    {
        $values = [
            'topic' => $topic->getTopic(),
            'description' => $topic->getDescription(),
        ];

        $this->query->insertInto($this->table, $values)->execute();

        return $this;
    }

    public function updateEventId(Topic $topic): self
    {
        $values = [
            'eventId' => $topic->getEventId(),
        ];
        $this->query->update($this->table, $values, $topic->getId())->execute();

        return $this;
    }

    public function findByEventId(int $id): bool|array
    {
        return $this->query->from($this->table)
            ->where('eventId', $id)
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
