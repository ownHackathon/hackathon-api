<?php declare(strict_types=1);

namespace Test\Unit\Mock\Service;

use App\Entity\Event;
use App\Hydrator\ReflectionHydrator;
use App\Service\Event\EventService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use Test\Unit\Mock\Table\MockEventTable;
use Test\Unit\Mock\TestConstants;

class MockEventService extends EventService
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
        if ($id === TestConstants::EVENT_ID) {
            return new Event();
        }

        throw new InvalidArgumentException('Could not find Event', HTTP::STATUS_BAD_REQUEST);
    }

    public function findByTitle(string $topic): ?Event
    {
        if ($topic === TestConstants::EVENT_TITLE_THROW_EXCEPTION) {
            throw new InvalidArgumentException(code: HTTP::STATUS_BAD_REQUEST);
        }
        if ($topic === TestConstants::EVENT_TITLE) {
            return new Event();
        }

        return null;
    }
}
