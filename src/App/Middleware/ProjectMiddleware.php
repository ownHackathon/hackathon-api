<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Project;
use App\Service\ProjectService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProjectMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ProjectService $projectService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $projectId = (int)$request->getAttribute('projectId');

        $project = $this->projectService->findById($projectId);

        return $handler->handle($request->withAttribute(Project::class, $project));
    }
}
