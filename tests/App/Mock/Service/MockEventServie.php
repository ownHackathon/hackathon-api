<?php declare(strict_types=1);

namespace App\Test\Mock\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\Event;
use App\Service\EventService;
use App\Test\Mock\Table\MockEventTable;

class MockEventServie extends EventService
{
    public function __construct()
    {
        parent::__construct(new MockEventTable(), new ReflectionHydrator());
    }

    public function create(Event $event): bool
    {
        return $event->getId() === 2;
    }
}
