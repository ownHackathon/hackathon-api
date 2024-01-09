<?php declare(strict_types=1);

namespace Test\Unit\App\Mock\Table;

use App\Table\EventTable;
use Test\Unit\App\Mock\Database\MockQuery;
use Test\Unit\App\Mock\TestConstants;

class MockEventTable extends EventTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function findById(int $id): array
    {
        if ($id === 1) {
            return [
                'id' => $id,
                'ratingCompleted' => true,
            ];
        }
        if ($id === 2) {
            return [
                'id' => $id,
                'ratingCompleted' => false,
            ];
        }

        return [];
    }

    public function findByTitle(string $topic): bool|array
    {
        if ($topic === TestConstants::EVENT_TITLE) {
            return [
                'title' => $topic,
            ];
        }

        return false;
    }
}
