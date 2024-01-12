<?php declare(strict_types=1);

namespace App\Repository;

interface ProjectRepository extends Repository
{
    public function findByParticipantId(int $id): bool|array;
}
