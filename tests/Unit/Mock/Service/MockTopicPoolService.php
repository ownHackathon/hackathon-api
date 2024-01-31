<?php declare(strict_types=1);

namespace Test\Unit\Mock\Service;

use App\Entity\Topic;
use App\Hydrator\ReflectionHydrator;
use App\Service\Topic\TopicPoolService;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use Test\Unit\Mock\Table\MockTopicPoolTable;

class MockTopicPoolService extends TopicPoolService
{
    private ReflectionHydrator $hydrator;

    public function __construct()
    {
        $this->hydrator = new ReflectionHydrator();
        $this->hydrator->addStrategy(
            'topic',
            new HydratorStrategy(new ReflectionHydrator(), Topic::class)
        );
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
