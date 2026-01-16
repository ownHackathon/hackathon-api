<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Participant;
use Core\Repository\Repository;

interface ParticipantRepository extends Repository
{
    public function insert(Participant $participant): int;

    public function remove(Participant $participant): bool;

    public function findByUserId(int $userId): array;

    public function findUserForAnEvent(int $userId, int $eventId): array;

    public function findActiveParticipantsByEvent(int $eventId): array;
}
