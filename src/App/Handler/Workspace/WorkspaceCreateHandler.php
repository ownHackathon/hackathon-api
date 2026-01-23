<?php declare(strict_types=1);

namespace App\Handler\Workspace;

use App\DTO\Response\HttpResponseMessage;
use App\DTO\Workspace\Workspace;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

readonly class WorkspaceCreateHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/workspace',
        operationId: 'createWorkspace',
        description: 'Creates a new workspace for the authenticated user. Workspaces are used to group related resources (e.g., events).',
        summary: 'Create a new workspace',
        security: [['accessToken' => []]],
        tags: ['Workspace'],
    )]
    #[OA\RequestBody(
        description: 'The data for the new workspace. At a minimum, a name is required.',
        required: true,
        content: new OA\JsonContent(ref: Workspace::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_CREATED,
        description: 'Workspace created successfully. The response contains the details of the newly created workspace.',
        content: new OA\JsonContent(ref: Workspace::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: 'Invalid input. The provided workspace name may be empty, too long, or contain invalid characters.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Authentication failed. The access token is missing or invalid.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_CONFLICT, // 409 for duplicate resources
        description: 'A workspace with this name already exists.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class)
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([], HTTP::STATUS_CREATED);
    }
}
