<?php declare(strict_types=1);

namespace App\Middleware\Project;

use App\Model\Participant;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProjectOwnerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Participant $participant */
        $participant = $request->getAttribute(Participant::class);

        $projectOwner = $this->userService->findById($participant->getUserId());

        return $handler->handle($request->withAttribute('projectOwner', $projectOwner));
    }
}
