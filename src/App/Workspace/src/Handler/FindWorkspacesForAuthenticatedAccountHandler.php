<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Handler;

use Exdrals\Identity\DTO\Response\HttpResponseMessage;
use ownHackathon\Workspace\DTO\WorkspaceList;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Attributes as OA;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;

readonly class FindWorkspacesForAuthenticatedAccountHandler implements RequestHandlerInterface
{
    #[OA\Get(
        path: '/workspace',
        operationId: 'findWorkspaces',
        description: 'List all workspaces for the authenticated account',
        summary: 'Returns a collection of all workspaces owned by or associated with the currently authenticated user.',
        security: [['accessToken' => []]],
        tags: ['Workspace'],
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'A list of workspaces belonging to the user.',
        content: new OA\JsonContent(ref: WorkspaceList::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Authentication failed. The access token is missing or invalid.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class)
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([], HTTP::STATUS_OK);
    }
}
