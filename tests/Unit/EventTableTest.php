<?php declare(strict_types=1);

namespace Tests\Unit;

use Envms\FluentPDO\Query;
use Envms\FluentPDO\Queries\Insert;
use ownHackathon\App\Event\Infrastructure\Persistence\Table\EventTable;

use function expect;
use function test;

test('event table persists data with all uniqueness fields', function (): void {
    $query = $this->createMock(Query::class);
    $insert = $this->createMock(Insert::class);
    $query->expects($this->once())->method('insertInto')->with(
        'Event',
        ['uuid' => 'uuid', 'name' => 'name', 'slug' => 'slug'],
    )->willReturn($insert);
    $insert->expects($this->once())->method('execute')->willReturn(17);

    expect((new EventTable($query))->persist([
        'id' => 3, 'uuid' => 'uuid', 'name' => 'name', 'slug' => 'slug',
    ]))->toBe(17);
});
