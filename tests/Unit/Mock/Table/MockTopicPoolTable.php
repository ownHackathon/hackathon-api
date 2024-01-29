<?php declare(strict_types=1);

namespace Test\Unit\Mock\Table;

use App\Entity\Topic;
use App\Table\TopicPoolTable;
use Test\Unit\Mock\Database\MockQuery;

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

    public function assignAnEvent(Topic $topic): TopicPoolTable
    {
        return $this;
    }

    public function findById(int $id): array
    {
        return $id === 1 ? ['id' => $id] : [];
    }

    public function findByEventId(int $eventId): bool|array
    {
        return $eventId === 1 ? ['eventId' => $eventId] : false;
    }

    public function findByTopic(string $topic): bool|array
    {
        return $topic === 'fakeTopic' ? ['topic' => $topic] : false;
    }
}
