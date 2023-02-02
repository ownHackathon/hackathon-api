<?php declare(strict_types=1);

namespace App\Table;

use App\Model\Event;

class EventTable extends AbstractTable
{
    public function insert(Event $event): int|bool
    {
        $values = [
            'userId' => $event->getUserId(),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'eventText' => $event->getEventText(),
            'startTime' => $event->getStartTime()->format('Y-m-d H:i'),
            'duration' => $event->getDuration(),
        ];

        $insertStatus = $this->query->insertInto($this->table, $values)->execute();

        return !$insertStatus ? false : (int)$insertStatus;
    }

    public function findAll(string $order = 'startTime', string $sort = 'ASC'): bool|array
    {
        return $this->query->from($this->table)->orderBy($order . ' ' . $sort)->fetchAll();
    }

    public function findByTitle(string $topic): bool|array
    {
        return $this->query->from($this->table)
            ->where('title', $topic)
            ->fetch();
    }

    public function findAllActive(): bool|array
    {
        return $this->query->from($this->table)
            ->where('active', 1)
            ->orderBy('startTime DESC')
            ->fetchAll();
    }

    public function findAllNotActive(): bool|array
    {
        return $this->query->from($this->table)
            ->where('active', 0)
            ->orderBy('startTime DESC')
            ->fetchAll();
    }

    public function remove(Event $event): bool|int
    {
        return $this->query->deleteFrom($this->table)
            ->where('id', $event->getId())
            ->execute();
    }
}
