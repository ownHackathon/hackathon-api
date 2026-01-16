<?php declare(strict_types=1);

namespace Test\Unit\Mock\Service;

use App\Entity\Topic;
use App\Service\Topic\TopicPoolService;
use Core\Hydrator\ReflectionHydrator;
use Test\Data\Entity\TopicTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Table\MockTopicPoolTable;

readonly class MockTopicPoolService extends TopicPoolService
{
    public function __construct()
    {
        parent::__construct(new MockTopicPoolTable(), new ReflectionHydrator());
    }

    public function findAvailable(): ?array
    {
        return [new Topic(...TopicTestEntity::getDefaultTopicValue())];
    }

    public function findByTopic(string $topic): ?Topic
    {
        if ($topic === TestConstants::TOPIC_TITLE) {
            return new Topic(...TopicTestEntity::getDefaultTopicValue());
        }

        return null;
    }
}
