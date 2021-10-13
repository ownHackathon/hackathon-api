<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Participant;
use App\Table\ParticipantTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\ReflectionHydrator;
use Psr\Log\InvalidArgumentException;

class ParticipantService
{
    public function __construct(
        private ParticipantTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
    }

    public function findById(int $id): Participant
    {
        $participant = $this->table->findById($id);

        if (!$participant) {
            throw new InvalidArgumentException('Could not find Participant', 400);
        }

        return $this->hydrator->hydrate($participant, new Participant());
    }

    public function findActiveParticipantByEvent(int $eventId): ?array
    {
        $participants = $this->table->findActiveParticipantByEvent($eventId);

        if (!$participants) {
            return null;
        }

        return $this->convertArrayToEventsArray($participants);
    }

    private function convertArrayToEventsArray(array $events): array
    {
        $eventsArray = [];

        foreach ($events as $event) {
            $eventsArray[] = $this->hydrator->hydrate($event, new Participant());
        }

        return $eventsArray;
    }
}
