<?php declare(strict_types=1);

namespace App\Table;

use App\Entity\Event;
use App\Repository\EventRepository;

class EventTable extends AbstractTable implements EventRepository
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

    public function findAll(string $order = 'startTime', string $sort = 'DESC'): array
    {
        return $this->query->from($this->table)->orderBy($order . ' ' . $sort)->fetchAll() ?: [];
    }

    public function findByTitle(string $title): bool|array
    {
        return $this->query->from($this->table)
            ->where('title', $title)
            ->fetch();
    }

    public function findAllActive(): bool|array
    {
        return $this->query->from($this->table)
            ->where('active', 1)
            ->orderBy('startTime DESC')
            ->fetchAll();
    }

    public function findAllInactive(): bool|array
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
