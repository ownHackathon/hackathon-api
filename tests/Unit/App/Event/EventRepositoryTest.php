<?php declare(strict_types=1);

namespace Tests\Unit\App\Event;

use DateTimeImmutable;
use ownHackathon\App\Event\Domain\Event;
use ownHackathon\App\Event\Domain\EventCollection;
use ownHackathon\App\Event\Infrastructure\Hydrator\EventHydratorInterface;
use ownHackathon\App\Event\Infrastructure\Persistence\Repository\EventRepository;
use ownHackathon\App\Event\Infrastructure\Persistence\Table\EventStoreInterface;
use ownHackathon\App\Event\Domain\Enum\EventStatus;
use ownHackathon\App\Policy\Domain\Enum\Visibility;
use Ramsey\Uuid\Uuid;

use function expect;
use function test;

function eventFixture(): Event
{
    return new Event(
        1,
        Uuid::uuid4(),
        2,
        null,
        'Event',
        'event',
        null,
        null,
        EventStatus::DRAFT,
        Visibility::PUBLIC,
        new DateTimeImmutable('2024-01-01'),
        new DateTimeImmutable('2024-01-02'),
        new DateTimeImmutable('2024-01-01'),
    );
}

test('event repository delegates all read and write operations', function (): void {
    $event = eventFixture();
    $collection = new EventCollection();
    $collection[] = $event;
    $store = $this->createMock(EventStoreInterface::class);
    $hydrator = $this->createMock(EventHydratorInterface::class);
    $hydrator->method('extract')->willReturn(['id' => 1, 'name' => 'Event']);
    $hydrator->method('hydrate')->willReturn($event);
    $hydrator->method('hydrateCollection')->willReturn($collection);
    $store->expects($this->once())->method('persist')->with(['id' => 1, 'name' => 'Event'])->willReturn(1);
    $store->expects($this->once())->method('update')->with(1, ['id' => 1, 'name' => 'Event'])->willReturn(true);
    $store->expects($this->exactly(3))->method('fetchOne')->willReturn(['id' => 1]);
    $store->expects($this->once())->method('fetchMany')->with(['workspaceId' => 2])->willReturn([['id' => 1]]);
    $store->expects($this->once())->method('fetchAll')->willReturn([['id' => 1]]);
    $store->expects($this->once())->method('remove')->with(['id' => 1])->willReturn(true);
    $repository = new EventRepository($store, $hydrator);

    expect($repository->insert($event))->toBe(1)
        ->and($repository->update($event))->toBeTrue()
        ->and($repository->findOneById(1))->toBe($event)
        ->and($repository->findByWorkspaceId(2))->toBe($collection)
        ->and($repository->findOneByName('Event'))->toBe($event)
        ->and($repository->findOneBySlug('event'))->toBe($event)
        ->and($repository->findeAll())->toBe($collection)
        ->and($repository->deleteById(1))->toBeTrue();
});

test('event repository maps empty reads to null and empty collections', function (): void {
    $store = $this->createMock(EventStoreInterface::class);
    $hydrator = $this->createMock(EventHydratorInterface::class);
    $empty = new EventCollection();
    $store->method('fetchOne')->willReturn(null);
    $store->method('fetchMany')->willReturn([]);
    $store->method('fetchAll')->willReturn([]);
    $hydrator->expects($this->exactly(2))->method('hydrateCollection')->with([])->willReturn($empty);
    $repository = new EventRepository($store, $hydrator);

    expect($repository->findOneById(1))->toBeNull()
        ->and($repository->findByWorkspaceId(1))->toBe($empty)
        ->and($repository->findeAll())->toBe($empty);
});

test('event repository treats an invalid persisted entity as not found', function (): void {
    $store = $this->createMock(EventStoreInterface::class);
    $hydrator = $this->createMock(EventHydratorInterface::class);
    $store->method('fetchOne')->willReturn(['id' => 1]);
    $hydrator->method('hydrate')->willThrowException(new \UnexpectedValueException('invalid persisted data'));

    expect((new EventRepository($store, $hydrator))->findOneById(1))->toBeNull();
});
