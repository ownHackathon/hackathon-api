<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Project;
use App\Table\ProjectTable;
use Laminas\Hydrator\ClassMethodsHydrator;

class ProjectService
{
    public function __construct(
        private ProjectTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
    }

    public function findById(int $id): Project
    {
        $project = $this->table->findById($id);

        if (!$project) {
            throw new InvalidArgumentException('Could not find Project', 400);
        }
        return $this->hydrator->hydrate($project, new Project());
    }

    public function findByParticipantId(int $id): ?Project
    {
        $project = $this->table->findByParticipantId($id);

        if (!$project) {
            return null;
        }

        return $this->hydrator->hydrate($project, new Project());
    }
}
