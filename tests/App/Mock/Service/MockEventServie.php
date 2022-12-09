<?php declare(strict_types=1);

namespace App\Test\Mock\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\Event;
use App\Service\EventService;
use App\Test\Mock\Table\MockEventTable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Psr\Log\InvalidArgumentException;

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

    public function findById(int $id): Event
    {
        if ($id === 1) {
            return new Event();
        }

        throw new InvalidArgumentException('Could not find Event', HTTP::STATUS_BAD_REQUEST);
    }
}
