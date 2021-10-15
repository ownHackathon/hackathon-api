<?php declare(strict_types=1);

namespace App\Middleware;

use App\Service\ParticipantService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ParticipantUserMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ParticipantService $participantService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $participantId = $request->getAttribute('participantId');

        $participant = $this->participantService->findById($participantId);

        $userId = $participant->getUserId();

        return $handler->handle($request->withAttribute('userId', $userId));
    }
}
