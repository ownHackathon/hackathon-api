<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Participant;

interface ParticipantRepository extends Repository
{
    public function insert(Participant $participant): int|bool;

    public function remove(Participant $participant): int|bool;

    public function findByUserId(int $userId): bool|array;

    public function findByUserIdAndEventId(int $userId, int $eventId): bool|array;

    public function findActiveParticipantByEvent(int $eventId): array;
}
