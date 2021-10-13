<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Model\Project;
use App\Service\ProjectService;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProjectMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ProjectService $projectService,
        private UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $projectId = (int)$request->getAttribute('projectId');

        $project = $this->projectService->findById($projectId);

        $participant = $this->userService->findById($project->getParticipantId());

        return $handler->handle(
            $request->withAttribute(Project::class, $project)
                ->withAttribute('participant', $participant)
        );
    }
}
