<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\Topic;
use App\Service\TopicPoolService;
use Test\Unit\App\Mock\Table\MockTopicPoolTable;

class TopicPoolServiceTest extends AbstractService
{
    private TopicPoolService $service;
    private MockTopicPoolTable $table;

    protected function setUp(): void
    {
        parent::setUp();

        $this->table = new MockTopicPoolTable();
        $this->service = new TopicPoolService($this->table, $this->hydrator);
    }

    public function testCanInsertTopic(): void
    {
        $insertTopic = $this->service->insert(new Topic());

        $this->assertInstanceOf(TopicPoolService::class, $insertTopic);
    }

    public function testCanUpdateEventId(): void
    {
        $updateTopic = $this->service->updateEventId(new Topic());

        $this->assertInstanceOf(TopicPoolService::class, $updateTopic);
    }

    public function testFindByIdThrowException(): void
    {
        $this->expectException('InvalidArgumentException');

        $this->service->findById(2);
    }

    public function testCanFindById(): void
    {
        $topic = $this->service->findById(1);

        $this->assertInstanceOf(Topic::class, $topic);
    }

    public function testCanNotFindByEventId(): void
    {
        $topic = $this->service->findByEventId(2);

        $this->assertNull($topic);
    }

    public function testCanFindByEventId(): void
    {
        $topic = $this->service->findByEventId(1);

        $this->assertInstanceOf(Topic::class, $topic);
    }

    public function testCanFindAvailable(): void
    {
        $topic = $this->service->findAvailable();

        $this->assertIsArray($topic);
        $this->assertArrayHasKey(0, $topic);
        $this->assertInstanceOf(Topic::class, $topic[0]);
    }

    public function testCanFindAll(): void
    {
        $topic = $this->service->findAll();

        $this->assertIsArray($topic);
        $this->assertArrayHasKey(0, $topic);
        $this->assertInstanceOf(Topic::class, $topic[0]);
    }

    public function testIsNotTopic(): void
    {
        $topic = $this->service->isTopic('fakeIsNotTopic');

        $this->assertSame(false, $topic);
    }

    public function testIsTopic(): void
    {
        $topic = $this->service->isTopic('fakeTopic');

        $this->assertSame(true, $topic);
    }

    public function testCanGetEntriesStatistic(): void
    {
        $values = [
            'allTopic' => $this->table->getCountTopic(),
            'allAcceptedTopic' => $this->table->getCountTopicAccepted(),
            'allSelectionAvailableTopic' => $this->table->getCountTopicSelectionAvailable(),
        ];

        $statistic = $this->service->getEntriesStatistic();

        $this->assertSame($values, $statistic);
    }
}
