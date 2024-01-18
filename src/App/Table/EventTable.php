<?php declare(strict_types=1);

namespace App\Table;

use App\Entity\Event;
use App\Exception\DuplicateEntryException;
use App\Repository\EventRepository;

class EventTable extends AbstractTable implements EventRepository
{
    public function insert(Event $event): int
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

        if (!$insertStatus) {
            throw new DuplicateEntryException('Event', $event->getId());
        }

        return (int)$insertStatus;
    }

    public function findAll(string $order = 'startTime', string $sort = 'DESC'): array
    {
        $result = $this->query->from($this->table)->orderBy($order . ' ' . $sort)->fetchAll();

        return $result ?: [];
    }

    public function findByTitle(string $title): array
    {
        $result = $this->query->from($this->table)
            ->where('title', $title)
            ->fetch();

        return $result ?: [];
    }

    public function findAllActive(): array
    {
        $result = $this->query->from($this->table)
            ->where('active', 1)
            ->orderBy('startTime DESC')
            ->fetchAll();

        return $result ?: [];
    }

    public function findAllInactive(): array
    {
        $result = $this->query->from($this->table)
            ->where('active', 0)
            ->orderBy('startTime DESC')
            ->fetchAll();

        return $result ?: [];
    }

    public function remove(Event $event): bool
    {
        return $this->query->deleteFrom($this->table)
            ->where('id', $event->getId())
            ->execute();
    }
}
