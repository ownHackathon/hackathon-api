<?php declare(strict_types=1);

namespace App\Table;

use App\Entity\Event;
use App\Repository\EventRepository;
use Core\Exception\DuplicateEntryException;
use Core\Table\AbstractTable;

readonly class EventTable extends AbstractTable implements EventRepository
{
    public function insert(Event $event): int
    {
        $values = [
            'uuid' => $event->uuid->getHex()->toString(),
            'userId' => $event->userId,
            'title' => $event->title,
            'description' => $event->description,
            'eventText' => $event->eventText,
            'startedAt' => $event->startedAt->format('Y-m-d H:i'),
            'duration' => $event->duration,
        ];

        $insertStatus = $this->query->insertInto($this->table, $values)->execute();

        if (!$insertStatus) {
            throw new DuplicateEntryException('Event', $event->uuid->getHex()->toString());
        }

        return (int)$insertStatus;
    }

    public function findAll(string $order = 'startedAt', string $sort = 'DESC'): array
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
            ->orderBy('startedAt DESC')
            ->fetchAll();

        return $result ?: [];
    }

    public function findAllInactive(): array
    {
        $result = $this->query->from($this->table)
            ->where('active', 0)
            ->orderBy('startedAt DESC')
            ->fetchAll();

        return $result ?: [];
    }

    public function remove(Event $event): bool
    {
        return $this->query->deleteFrom($this->table)
            ->where('id', $event->id)
            ->execute();
    }
}
