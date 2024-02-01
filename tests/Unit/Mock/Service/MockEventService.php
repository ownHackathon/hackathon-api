<?php declare(strict_types=1);

namespace Test\Unit\Mock\Service;

use App\Entity\Event;
use App\Hydrator\ReflectionHydrator;
use App\Service\Event\EventService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use Test\Data\Entity\EventTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Table\MockEventTable;

class MockEventService extends EventService
{
    public function __construct()
    {
        parent::__construct(new MockEventTable(), new ReflectionHydrator());
    }

    public function create(Event $event): bool
    {
        return $event->id === TestConstants::EVENT_ID_NOT_REMOVED;
    }

    public function findById(int $id): Event
    {
        if ($id === TestConstants::EVENT_ID) {
            return new Event(...EventTestEntity::getDefaultEventValue());
        }

        throw new InvalidArgumentException('Could not find Event', HTTP::STATUS_BAD_REQUEST);
    }

    public function findByTitle(string $topic): ?Event
    {
        if ($topic === TestConstants::EVENT_TITLE_THROW_EXCEPTION) {
            throw new InvalidArgumentException(code: HTTP::STATUS_BAD_REQUEST);
        }
        if ($topic === TestConstants::EVENT_TITLE) {
            return new Event(...EventTestEntity::getDefaultEventValue());
        }

        return null;
    }
}
