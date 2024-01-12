<?php declare(strict_types=1);

namespace App\Service\Participant;

use App\Entity\Participant;
use App\Hydrator\ReflectionHydrator;
use App\Repository\ParticipantRepository;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Psr\Log\InvalidArgumentException;

readonly class ParticipantService
{
    public function __construct(
        private ParticipantRepository $repository,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function create(Participant $participant): bool
    {
        if ($this->isParticipantInEventExist($participant->getUserId(), $participant->getEventId())) {
            return false;
        }

        return $this->repository->insert($participant) !== false;
    }

    public function remove(Participant $participant): bool
    {
        return (int)$this->repository->remove($participant) !== 0;
    }

    public function findById(int $id): Participant
    {
        $participant = $this->repository->findById($id);

        if ($participant === []) {
            throw new InvalidArgumentException(
                sprintf('Could not find Participant with id %d', $id),
                HTTP::STATUS_NOT_FOUND
            );
        }

        return $this->hydrator->hydrate($participant, new Participant());
    }

    public function findByUserId(int $userId): ?Participant
    {
        $participant = $this->repository->findByUserId($userId);

        return $this->hydrator->hydrate($participant, new Participant());
    }

    public function findByUserIdAndEventId(int $userId, int $eventId): ?Participant
    {
        $participant = $this->repository->findUserForAnEvent($userId, $eventId);

        return $this->hydrator->hydrate($participant, new Participant());
    }

    /**
     * @return array<Participant>|null
     */
    public function findActiveParticipantByEvent(int $eventId): ?array
    {
        $participants = $this->repository->findActiveParticipantsByEvent($eventId);

        return $this->hydrator->hydrateList($participants, Participant::class);
    }

    private function isParticipantInEventExist(int $userId, int $eventId): bool
    {
        $participant = $this->findByUserIdAndEventId($userId, $eventId);

        return $participant instanceof Participant;
    }
}
