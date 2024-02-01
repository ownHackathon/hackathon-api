<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\Topic;
use App\Repository\TopicPoolRepository;
use App\Service\Topic\TopicPoolService;
use InvalidArgumentException;
use Test\Data\Entity\TopicTestEntity;
use Test\Unit\Mock\Table\MockTopicPoolTable;
use Test\Data\TestConstants;

class TopicPoolServiceTest extends AbstractService
{
    private TopicPoolService $service;
    private TopicPoolRepository $table;

    protected function setUp(): void
    {
        parent::setUp();

        $this->table = new MockTopicPoolTable();
        $this->service = new TopicPoolService($this->table, $this->hydrator);
    }

    public function testCanInsertTopic(): void
    {
        $insertTopic = $this->service->insert(new Topic(...TopicTestEntity::getDefaultTopicValue()));

        self::assertInstanceOf(TopicPoolService::class, $insertTopic);
    }

    public function testCanUpdateEventId(): void
    {
        $updateTopic = $this->service->updateEventId(new Topic(...TopicTestEntity::getDefaultTopicValue()));

        self::assertInstanceOf(TopicPoolService::class, $updateTopic);
    }

    public function testFindByIdThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->service->findById(TestConstants::TOPIC_ID_THROW_EXCEPTION);
    }

    public function testCanFindById(): void
    {
        $topic = $this->service->findById(TestConstants::TOPIC_ID);

        self::assertInstanceOf(Topic::class, $topic);
    }

    public function testCanNotFindByEventId(): void
    {
        $topic = $this->service->findByEventId(TestConstants::EVENT_ID_UNUSED);

        $this->assertNull($topic);
    }

    public function testCanFindByEventId(): void
    {
        $topic = $this->service->findByEventId(TestConstants::EVENT_ID);

        self::assertInstanceOf(Topic::class, $topic);
    }

    public function testCanFindAvailable(): void
    {
        $topic = $this->service->findAvailable();

        self::assertIsArray($topic);
        self::assertArrayHasKey(0, $topic);
        self::assertInstanceOf(Topic::class, $topic[0]);
    }

    public function testCanFindAll(): void
    {
        $topic = $this->service->findAll();

        self::assertIsArray($topic);
        self::assertArrayHasKey(0, $topic);
        self::assertInstanceOf(Topic::class, $topic[0]);
    }

    public function testIsNotTopic(): void
    {
        $topic = $this->service->isTopic('fakeIsNotTopic');

        self::assertSame(false, $topic);
    }

    public function testIsTopic(): void
    {
        $topic = $this->service->isTopic(TestConstants::TOPIC_TITLE);

        self::assertSame(true, $topic);
    }

    public function testCanGetEntriesStatistic(): void
    {
        $values = [
            'allTopic' => $this->table->getCountTopic(),
            'allAcceptedTopic' => $this->table->getCountTopicAccepted(),
            'allSelectionAvailableTopic' => $this->table->getCountTopicSelectionAvailable(),
        ];

        $statistic = $this->service->getEntriesStatistic();

        self::assertSame($values, $statistic);
    }
}
