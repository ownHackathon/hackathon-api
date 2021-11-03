<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use App\Model\TopicPool;
use Envms\FluentPDO\Query;

class TopicPoolTable extends AbstractTable
{
    public function __construct(Query $query)
    {
        parent::__construct($query, 'TopicPool');
    }

    public function findByTopic(string $topic): bool|array
    {
        return $this->query->from($this->table)
            ->where('`topic`', $topic)
            ->fetch();
    }

    public function insert(TopicPool $topic): self
    {
        $values = [
            'topic' => $topic->getTopic(),
            'description' => $topic->getDescription(),
        ];

        $this->query->insertInto($this->table, $values)->execute();

        return $this;
    }

    public function getCountTopic(): int
    {
        $data = $this->query->from($this->table)
            ->select('COUNT(`id`) AS countTopic')
            ->fetch();
        return $data['countTopic'];
    }

    public function getCountTopicAccepted(): int
    {
        $data = $this->query->from($this->table)
            ->select('COUNT(`id`) AS countTopic')
            ->where('`accepted`', 1)
            ->fetch();
        return $data['countTopic'];
    }

    public function getCountTopicSelectionAvailable(): int
    {
        $data = $this->query->from($this->table)
            ->select('COUNT(`id`) AS countTopic')
            ->where('`accepted`', 1)
            ->where('`eventId`', null)
            ->fetch();
        return $data['countTopic'];
    }
}
