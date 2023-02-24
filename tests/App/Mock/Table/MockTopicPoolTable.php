<?php declare(strict_types=1);

namespace App\Test\Mock\Table;

use App\Entity\Topic;
use App\Table\TopicPoolTable;
use App\Test\Mock\Database\MockQuery;

class MockTopicPoolTable extends TopicPoolTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function insert(Topic $topic): TopicPoolTable
    {
        return $this;
    }

    public function updateEventId(Topic $topic): TopicPoolTable
    {
        return $this;
    }

    public function findById(int $id): bool|array
    {
        return $id === 1 ? ['id' => $id] : false;
    }

    public function findByEventId(int $id): bool|array
    {
        return $id === 1 ? ['eventId' => $id] : false;
    }

    public function findByTopic(string $topic): bool|array
    {
        return $topic === 'fakeTopic' ? ['topic' => $topic] : false;
    }
}
