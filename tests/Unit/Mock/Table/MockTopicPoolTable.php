<?php declare(strict_types=1);

namespace Test\Unit\Mock\Table;

use App\Entity\Topic;
use App\Table\TopicPoolTable;
use Test\Data\Entity\TopicTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Database\MockQuery;

readonly class MockTopicPoolTable extends TopicPoolTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function insert(Topic $topic): TopicPoolTable
    {
        return $this;
    }

    public function assignAnEvent(int $topicId, int $eventId): TopicPoolTable
    {
        return $this;
    }

    public function findById(int $id): array
    {
        return $id === TestConstants::TOPIC_ID ? ['id' => $id] + TopicTestEntity::getDefaultTopicValue() : [];
    }

    public function findByEventId(int $eventId): array
    {
        return $eventId === TestConstants::EVENT_ID
            ? ['eventId' => $eventId] + TopicTestEntity::getDefaultTopicValue()
            : [];
    }

    public function findByTopic(string $topic): array
    {
        return $topic === TestConstants::TOPIC_TITLE
            ? ['topic' => $topic] + TopicTestEntity::getDefaultTopicValue()
            : [];
    }
}
