<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Project;
use App\Table\ProjectTable;
use App\Hydrator\ReflectionHydrator;
use Psr\Log\InvalidArgumentException;

class ProjectService
{
    public function __construct(
        private ProjectTable $table,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function findById(int $id): Project
    {
        $project = $this->table->findById($id);

        if (!$project) {
            throw new InvalidArgumentException('Could not find Project', 400);
        }

        return $this->generateProjectObject($project);
    }

    public function findByParticipantId(int $id): ?Project
    {
        $project = $this->table->findByParticipantId($id);

        return $this->generateProjectObject($project);
    }

    private function generateProjectObject(bool|array $project): ?Project
    {
        if (!$project) {
            return null;
        }

        return $this->hydrator->hydrate($project, new Project());
    }
}
