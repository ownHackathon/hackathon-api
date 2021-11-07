<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use App\Model\Event;
use Envms\FluentPDO\Query;

class EventTable extends AbstractTable
{
    public function __construct(Query $query)
    {
        parent::__construct($query, 'Event');
    }

    public function insert(Event $event): self
    {
        $values = [
            'userId' => $event->getUserId(),
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'eventText' => $event->getEventText(),
            'startTime' => $event->getStartTime()->format('Y-m-d H:i'),
            'duration' => $event->getDuration(),
        ];

        $this->query->insertInto($this->table, $values)->execute();

        return $this;
    }

    public function findByTopic(string $topic): bool|array
    {
        return $this->query->from($this->table)
            ->where('name', $topic)
            ->fetch();
    }

    public function findAllActive(): bool|array
    {
        return $this->query->from($this->table)
            ->where('active', 1)
            ->fetchAll();
    }

    public function findAllNotActive(): bool|array
    {
        return $this->query->from($this->table)
            ->where('active', 0)
            ->fetchAll();
    }
}
