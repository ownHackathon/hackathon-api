<?php declare(strict_types=1);

namespace Tests\Unit\Core\Persistence;

use ownHackathon\Core\Persistence\Pagination;

use function expect;
use function test;

test('pagination normalizes bounds and calculates offset', function (): void {
    $pagination = Pagination::fromParams([]);
    expect([$pagination->page, $pagination->limit, $pagination->offset])->toBe([1, 5, 0])
        ->and(Pagination::fromParams(['page' => 3, 'limit' => 10])->offset)->toBe(20)
        ->and(Pagination::fromParams(['limit' => 999])->limit)->toBe(250)
        ->and(Pagination::fromParams(['page' => 0, 'limit' => 0])->page)->toBe(1)
        ->and(Pagination::fromParams(['page' => 2, 'limit' => -5])->limit)->toBe(1);
});