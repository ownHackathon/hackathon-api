<?php declare(strict_types=1);

namespace Test\Unit\Mock\Service;

use App\Entity\Topic;
use App\Hydrator\ReflectionHydrator;
use App\Service\TopicPoolService;
use Test\Unit\Mock\Table\MockTopicPoolTable;

class MockTopicPoolService extends TopicPoolService
{
    public function __construct()
    {
        parent::__construct(new MockTopicPoolTable(), new ReflectionHydrator());
    }

    public function findAvailable(): ?array
    {
        return [new Topic()];
    }

    public function findByTopic(string $topic): ?Topic
    {
        if ($topic === 'duplicated') {
            return new Topic();
        }

        return null;
    }
}
