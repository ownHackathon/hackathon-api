<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Handler;

use Exdrals\Core\Shared\Domain\Enum\DateTimeFormat;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Workspace\DTO\Workspace;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class WorkspaceHandler implements RequestHandlerInterface
{
    public function __construct(
        private WorkspaceRepositoryInterface $workspaceRepository,
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    #[OA\Get(
        path: '/workspace/{slug}',
        operationId: 'findWorkspaceBySlug',
        description: 'Returns the details of a workspace identified by its slug.',
        summary: 'Get a workspace by slug',
        security: [['accessToken' => []]],
        tags: ['Workspace'],
    )]
    #[OA\Parameter(
        name: 'slug',
        description: 'The unique slug of the workspace.',
        in: 'path',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            pattern: '^[a-zA-Z0-9\\-]+$',
            example: 'my-own-workspace',
        )
    )]
    #[OA\Response(
        response: Http::STATUS_OK,
        description: 'Workspace details retrieved successfully.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', description: 'The name of the workspace.', type: 'string', example: 'My own workspace'),
                new OA\Property(
                    property: 'description',
                    description: 'The description of the workspace.',
                    type: 'string',
                    example: 'My own workspace is wonderfully',
                    nullable: true,
                ),
                new OA\Property(property: 'owner', description: 'The name of the workspace owner.', type: 'string', example: 'Jane Doe'),
                new OA\Property(
                    property: 'ownerUuid',
                    description: 'The UUID of the workspace owner.',
                    type: 'string',
                    format: 'uuid',
                    example: '019becbe-f952-7b82-82fa-f41f8ae24599',
                ),
                new OA\Property(property: 'details', description: 'Additional workspace details.', type: 'string', nullable: true),
                new OA\Property(
                    property: 'visibility',
                    description: 'The visibility level of the workspace: ' .
                        '100 = Private, 200 = Internal, 300 = Friends only, 400 = Invite only, ' .
                        '500 = Registered User, 600 = Unlisted, 700 = Public.',
                    type: 'integer',
                    enum: [100, 200, 300, 400, 500, 600, 700],
                    example: 700,
                ),
                new OA\Property(property: 'createdAt', description: 'The creation date of the workspace.', type: 'string', example: '2026-08-26 12:00:00'),
                new OA\Property(property: 'updatedAt', description: 'The date the workspace was last updated.', type: 'string', example: '2026-08-26 12:00:00'),
            ],
            type: 'object',
        )
    )]
    #[OA\Response(
        response: Http::STATUS_UNAUTHORIZED,
        description: 'Authentication failed. The access token is missing or invalid.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'statusCode', type: 'integer', example: Http::STATUS_UNAUTHORIZED),
                new OA\Property(property: 'message', type: 'string', example: 'Unauthorized access'),
            ],
            type: 'object',
        )
    )]
    #[OA\Response(
        response: Http::STATUS_NOT_FOUND,
        description: 'No workspace with the given slug was found.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'statusCode', type: 'integer', example: Http::STATUS_NOT_FOUND),
                new OA\Property(property: 'message', type: 'string', example: 'Workspace not found'),
            ],
            type: 'object',
        )
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $slug = $request->getAttribute('slug');

        $workspace = $this->workspaceRepository->findOneBySlug($slug);
        if ($workspace === null) {
            return new JsonResponse([
                'statusCode' => Http::STATUS_NOT_FOUND,
                'message' => 'Workspace not found',
            ], Http::STATUS_NOT_FOUND);
        }

        $account = $this->accountRepository->findOneById($workspace->accountId);

        $response = Workspace::fromArray(
            [
                'name' => $workspace->name,
                'description' => $workspace->description,
                'owner' => $account->name,
                'ownerUuid' => $account->uuid->toString(),
                'details' => $workspace->details,
                'visibility' => $workspace->visibility->value,
                'createdAt' => $workspace->createdAt->format(DateTimeFormat::DEFAULT->value),
                'updatedAt' => $workspace->updatedAt->format(DateTimeFormat::DEFAULT->value),
            ]
        );

        return new JsonResponse($response, Http::STATUS_OK);
    }
}
