<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Hydrator\ReflectionHydrator;
use App\Repository\ProjectRepository;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;

use function sprintf;

readonly class ProjectService
{
    public function __construct(
        private ProjectRepository $repository,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function findById(int $id): ?Project
    {
        $project = $this->repository->findById($id);

        if ($project === []) {
            throw new InvalidArgumentException(
                sprintf('Project with id %d not found', $id),
                HTTP::STATUS_NOT_FOUND
            );
        }

        return $this->hydrator->hydrate($project, new Project());
    }

    public function findByParticipantId(int $id): ?Project
    {
        $project = $this->repository->findByParticipantId($id);

        return $this->hydrator->hydrate($project, new Project());
    }
}
