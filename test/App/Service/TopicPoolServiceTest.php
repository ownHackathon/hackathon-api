<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Event;
use App\Model\Topic;
use App\Table\TopicPoolTable;
use Laminas\Hydrator\ReflectionHydrator;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

class TopicPoolServiceTest extends TestCase
{
    public function testCanInsertTopic(): void
    {
        $table = $this->createMock(TopicPoolTable::class);
        $hydrator = new ReflectionHydrator();

        $table->expects($this->once())
            ->method('insert')
            ->with(new Topic())
            ->willReturnSelf();

        $service = new TopicPoolService($table, $hydrator);

        $insertTopic = $service->insert(new Topic());

        $this->assertInstanceOf(TopicPoolService::class, $insertTopic);
    }

    public function testCanUpdateEventId(): void
    {
        $table = $this->createMock(TopicPoolTable::class);
        $hydrator = new ReflectionHydrator();

        $table->expects($this->once())
            ->method('updateEventId')
            ->with(new Topic())
            ->willReturnSelf();

        $service = new TopicPoolService($table, $hydrator);

        $updateTopic = $service->updateEventId(new Topic());

        $this->assertInstanceOf(TopicPoolService::class, $updateTopic);
    }

    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(TopicPoolTable::class);
        $hydrator = new ReflectionHydrator();

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(false);

        $service = new TopicPoolService($table, $hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(TopicPoolTable::class);
        $hydrator = new ReflectionHydrator();

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(['id' => 1]);

        $service = new TopicPoolService($table, $hydrator);

        $topic = $service->findById(1);

        $this->assertInstanceOf(Topic::class, $topic);
    }
}
