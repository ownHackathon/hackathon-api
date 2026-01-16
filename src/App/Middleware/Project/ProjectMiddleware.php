<?php declare(strict_types=1);

namespace App\Middleware\Project;

use App\Entity\Project;
use App\Service\Project\ProjectService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ProjectMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ProjectService $projectService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $projectId = (int)$request->getAttribute('projectId');

        $project = $this->projectService->findById($projectId);

        return $handler->handle($request->withAttribute(Project::class, $project));
    }
}
