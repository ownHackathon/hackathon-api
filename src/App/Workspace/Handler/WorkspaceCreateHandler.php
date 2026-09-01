<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Handler;

use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Helper\UrlHelper;
use OpenApi\Attributes as OA;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Workspace\Application\Port\WorkspaceCreatorInterface;
use ownHackathon\App\Workspace\Domain\Exception\WorkspaceNameAlreadyExistsException;
use ownHackathon\App\Workspace\Domain\Message\WorkspaceLogMessage;
use ownHackathon\App\Workspace\Domain\Message\WorkspaceStatusMessage;
use ownHackathon\App\Workspace\DTO\WorkspaceRequest;
use ownHackathon\App\Workspace\DTO\WorkspaceResponse;
use ownHackathon\Core\Http\Exception\HttpDuplicateEntryException;
use ownHackathon\Core\Http\DTO\HttpResponseMessage;
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
        response: Http::STATUS_CREATED,
        description: 'Workspace created successfully. The response contains the details of the newly created workspace.',
        content: new OA\JsonContent(ref: WorkspaceResponse::class)
    )]
    #[OA\Response(
        response: Http::STATUS_BAD_REQUEST,
        description: 'Invalid input. The provided workspace name may be empty, too long, or contain invalid characters.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class)
    )]
    #[OA\Response(
        response: Http::STATUS_UNAUTHORIZED,
        description: 'Authentication failed. The access token is missing or invalid.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class)
    )]
    #[OA\Response(
        response: Http::STATUS_CONFLICT,
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
        $location = $location !== '/' ? rtrim($location, '/') : $location;

        $response = WorkspaceResponse::fromEntity($workspace, $account);

        return new JsonResponse($response, Http::STATUS_CREATED, headers: [
            'Location' => $location,
        ]);
    }
}
