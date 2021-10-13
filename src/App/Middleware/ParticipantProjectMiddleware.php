<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Model\Project;
use App\Model\User;
use App\Service\ProjectService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ParticipantProjectMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ProjectService $projectService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $participants = $request->getAttribute('participants');

        $projects = [];

        /** @var User $participant */
        foreach ($participants as $participant) {
            $project = $this->projectService->findByParticipantId($participant->getId());
            $projects[] = [
                User::class => $participant,
                Project::class => $project,
            ];
        }

        return $handler->handle($request->withAttribute('participants', $projects));
    }
}
