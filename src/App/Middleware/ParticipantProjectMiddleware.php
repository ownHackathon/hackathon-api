<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Participant;
use App\Model\Project;
use App\Model\User;
use App\Service\ProjectService;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ParticipantProjectMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly UserService $userService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $participants = $request->getAttribute('participants');

        $projects = [];

        if (null !== $participants) {
            /** @var Participant $participant */
            foreach ($participants as $participant) {
                $user = $this->userService->findById($participant->getUserId());
                $project = $this->projectService->findByParticipantId($participant->getId());
                $projects[] = [
                    User::class => $user,
                    Project::class => $project,
                ];
            }
        }

        return $handler->handle($request->withAttribute('participants', $projects));
    }
}
