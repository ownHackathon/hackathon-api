<?php declare(strict_types=1);

namespace App\Test\Mock\Table;

use App\Table\EventTable;
use App\Test\Mock\Database\MockQuery;

class MockEventTable extends EventTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function findById(int $id): bool|array
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

        return false;
    }

    public function findByTitle(string $topic): bool|array
    {
        if ($topic === 'fakeEvent') {
            return false;
        }

        return [
            'title' => $topic,
        ];
    }
}
