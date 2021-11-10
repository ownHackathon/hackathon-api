<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Participant;
use App\Table\ParticipantTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Log\InvalidArgumentException;

class ParticipantService
{
    use ConvertArrayToClassArrayTrait;

    public function __construct(
        private ParticipantTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
    }

    public function create(Participant $participant): bool
    {
        if ($this->isParticipantInEventExist($participant->getUserId(), $participant->getEventId())) {
            return false;
        }

        $this->table->insert($participant);

        return true;
    }

    public function findById(int $id): Participant
    {
        $participant = $this->table->findById($id);

        if (!$participant) {
            throw new InvalidArgumentException('Could not find Participant', 400);
        }

        return $this->hydrator->hydrate($participant, new Participant());
    }

    public function findByUserId(int $userId): ?Participant
    {
        $participant = $this->table->findByUserId($userId);

        if (!$participant) {
            return null;
        }

        return $this->hydrator->hydrate($participant, new Participant());
    }

    public function findByUserIdAndEventId(int $userId, int $eventId): ?Participant
    {
        $participant = $this->table->findByUserIdAndEventId($userId, $eventId);

        if (!$participant) {
            return null;
        }

        return $this->hydrator->hydrate($participant, new Participant());
    }

    /** @return null|Participant[] */
    public function findActiveParticipantByEvent(int $eventId): ?array
    {
        $participants = $this->table->findActiveParticipantByEvent($eventId);

        if (!$participants) {
            return null;
        }

        return $this->convertArrayToClassArray($participants, Participant::class);
    }

    private function isParticipantExist(int $userId): bool
    {
        $participant = $this->findByUserId($userId);

        return $participant instanceof Participant;
    }

    private function isParticipantInEventExist(int $userId, int $eventId): bool
    {
        $participant = $this->findByUserIdAndEventId($userId, $eventId);

        return $participant instanceof Participant;
    }
}
