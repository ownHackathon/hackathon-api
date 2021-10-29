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

    public function insert(TopicPool $topic): self
    {
        $values = [
            'topic' => $topic->getTopic(),
            'description' => $topic->getDescription(),
        ];

        $this->query->insertInto($this->table, $values)->execute();

        return $this;
    }
}
