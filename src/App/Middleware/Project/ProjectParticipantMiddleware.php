<?php declare(strict_types=1);

namespace App\Middleware\Project;

use App\Entity\Participant;
use App\Entity\Project;
use App\Service\Participant\ParticipantService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ProjectParticipantMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ParticipantService $participantService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var Project $project
         */
        $project = $request->getAttribute(Project::class);

        $participant = $this->participantService->findById($project->getParticipantId());

        return $handler->handle($request->withAttribute(Participant::class, $participant));
    }
}
