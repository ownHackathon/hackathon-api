<?php declare(strict_types=1);

namespace App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\Project;
use App\Table\ProjectTable;

class ProjectService
{
    public function __construct(
        private readonly ProjectTable $table,
        private readonly ReflectionHydrator $hydrator,
    ) {
    }

    public function findById(int $id): ?Project
    {
        $project = $this->table->findById($id);

        if (!$project) {
            return null;
        }

        return $this->hydrator->hydrate($project, new Project());
    }

    public function findByParticipantId(int $id): ?Project
    {
        $project = $this->table->findByParticipantId($id);

        return $this->hydrator->hydrate($project, new Project());
    }
}
