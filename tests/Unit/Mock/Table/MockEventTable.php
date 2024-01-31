<?php declare(strict_types=1);

namespace Test\Unit\Mock\Table;

use App\Table\EventTable;
use Test\Unit\Mock\Database\MockQuery;
use Test\Unit\Mock\TestConstants;

readonly class MockEventTable extends EventTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function findById(int $id): array
    {
        return match ($id) {
            TestConstants::EVENT_ID => [
                'id' => $id,
                'ratingCompleted' => true,
            ],
            TestConstants::EVENT_ID_RATING_NOT_COMPLETED => [
                'id' => $id,
                'ratingCompleted' => false,
            ],
            default => []
        };
    }

    public function findByTitle(string $title): array
    {
        return match ($title) {
            TestConstants::EVENT_TITLE => [
                'title' => $title,
            ],
            default => []
        };
    }
}
