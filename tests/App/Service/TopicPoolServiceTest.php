<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Model\Topic;
use App\Service\TopicPoolService;
use App\Table\TopicPoolTable;

class TopicPoolServiceTest extends AbstractServiceTest
{
    public function testCanInsertTopic(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('insert')
            ->with(new Topic())
            ->willReturnSelf();

        $service = new TopicPoolService($table, $this->hydrator);

        $insertTopic = $service->insert(new Topic());

        $this->assertInstanceOf(TopicPoolService::class, $insertTopic);
    }

    public function testCanUpdateEventId(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('updateEventId')
            ->with(new Topic())
            ->willReturnSelf();

        $service = new TopicPoolService($table, $this->hydrator);

        $updateTopic = $service->updateEventId(new Topic());

        $this->assertInstanceOf(TopicPoolService::class, $updateTopic);
    }

    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(false);

        $service = new TopicPoolService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new TopicPoolService($table, $this->hydrator);

        $topic = $service->findById(1);

        $this->assertInstanceOf(Topic::class, $topic);
    }

    public function testCanFindByEventId(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('findByEventId')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new TopicPoolService($table, $this->hydrator);

        $topic = $service->findByEventId(1);

        $this->assertInstanceOf(Topic::class, $topic);
    }

    public function testCanFindAvailable(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('findAvailable')
            ->willReturn($this->fetchAllResult);

        $service = new TopicPoolService($table, $this->hydrator);

        $topic = $service->findAvailable();

        $this->assertIsArray($topic);
        $this->assertInstanceOf(Topic::class, $topic[0]);
    }

    public function testCanFindAll(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('findAll')
            ->willReturn($this->fetchAllResult);

        $service = new TopicPoolService($table, $this->hydrator);

        $topic = $service->findAll();

        $this->assertIsArray($topic);
        $this->assertInstanceOf(Topic::class, $topic[0]);
    }

    public function testIsNotTopic(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('findByTopic')
            ->willReturn(false);

        $service = new TopicPoolService($table, $this->hydrator);

        $topic = $service->isTopic('fakeTopic');

        $this->assertSame(false, $topic);
    }

    public function testIsTopic(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $table->expects($this->once())
            ->method('findByTopic')
            ->willReturn($this->fetchResult);

        $service = new TopicPoolService($table, $this->hydrator);

        $topic = $service->isTopic('fakeTopic');

        $this->assertSame(true, $topic);
    }

    public function testCanGetEntriesStatistic(): void
    {
        $table = $this->createMock(TopicPoolTable::class);

        $values = [
            'allTopic' => $table->getCountTopic(),
            'allAcceptedTopic' => $table->getCountTopicAccepted(),
            'allSelectionAvailableTopic' => $table->getCountTopicSelectionAvailable(),
        ];

        $service = new TopicPoolService($table, $this->hydrator);

        $statistic = $service->getEntriesStatistic();

        $this->assertSame($values, $statistic);
    }
}
