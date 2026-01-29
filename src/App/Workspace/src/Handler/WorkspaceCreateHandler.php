<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Handler;

use Exdrals\Identity\DTO\Response\HttpResponseMessage;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Exception\HttpDuplicateEntryException;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Helper\UrlHelper;
use OpenApi\Attributes as OA;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCreatorInterface;
use ownHackathon\Workspace\Domain\Exception\WorkspaceNameAlreadyExistsException;
use ownHackathon\Workspace\Domain\Message\WorkspaceLogMessage;
use ownHackathon\Workspace\Domain\Message\WorkspaceStatusMessage;
use ownHackathon\Workspace\DTO\WorkspaceRequest;
use ownHackathon\Workspace\DTO\WorkspaceResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class WorkspaceCreateHandler implements RequestHandlerInterface
{
    public function __construct(
        private WorkspaceCreatorInterface $workspaceCreator,
        private UrlHelper $urlHelper,
    ) {
    }

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
        content: new OA\JsonContent(ref: WorkspaceRequest::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_CREATED,
        description: 'Workspace created successfully. The response contains the details of the newly created workspace.',
        content: new OA\JsonContent(ref: WorkspaceResponse::class)
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
        response: HTTP::STATUS_CONFLICT,
        description: 'A workspace with this name already exists.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class)
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $workspace = $request->getAttribute(WorkspaceRequest::class);
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        try {
            $workspace = $this->workspaceCreator->create($workspace, $account);
        } catch (WorkspaceNameAlreadyExistsException $exception) {
            throw new HttpDuplicateEntryException(
                WorkspaceLogMessage::DUPLICATED_WORKSPACE_NAME,
                WorkspaceStatusMessage::DUPLICATED_WORKSPACE_NAME,
                [
                    'Workspace:' => $workspace->name,
                ]
            );
        }

        $location = $this->urlHelper->generate('api_workspace_detail', ['slug' => $workspace->slug]);

        $response = WorkspaceResponse::fromEntity($workspace, $account);

        return new JsonResponse($response, HTTP::STATUS_CREATED, headers: [
            'Location' => $location,
        ]);
    }
}
