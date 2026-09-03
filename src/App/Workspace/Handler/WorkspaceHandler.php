<?php declare(strict_types=1);

namespace App\Workspace\Handler;

use App\Account\Identity\Domain\AccountInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Policy\Domain\Enum\Visibility;
use App\Policy\Domain\VisibilityPolicyInterface;
use App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use App\Workspace\DTO\Workspace;
use Core\Clock\DateTimeFormat;
use Core\SharedKernel\Domain\Exception\EmptyResultException;
use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class WorkspaceHandler implements RequestHandlerInterface
{
    public function __construct(
        private WorkspaceRepositoryInterface $workspaceRepository,
        private AccountRepositoryInterface $accountRepository,
        private VisibilityPolicyInterface $visibilityPolicy,
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
        ),
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
                        Visibility::UNLISTED->value . ' = Unlisted, ' .
                        Visibility::REGISTERED->value . ' = Registered User, ' .
                        Visibility::PUBLIC->value . ' = Public.',
                    type: 'integer',
                    enum: [Visibility::UNLISTED->value, Visibility::REGISTERED->value, Visibility::PUBLIC->value],
                    example: Visibility::PUBLIC->value,
                ),
                new OA\Property(property: 'createdAt', description: 'The creation date of the workspace.', type: 'string', example: '2026-08-26 12:00:00'),
                new OA\Property(property: 'updatedAt', description: 'The date the workspace was last updated.', type: 'string', example: '2026-08-26 12:00:00'),
            ],
            type: 'object',
        ),
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
        ),
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
        ),
    )]
    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $slug = $request->getAttribute('slug');
        $user = $request->getAttribute(AccountInterface::AUTHENTICATED);

        try {
            $workspace = $this->workspaceRepository->findOneBySlug($slug);
        } catch (EmptyResultException) {
            $workspace = null;
        }
        if ($workspace === null || !$this->visibilityPolicy->isAvailableFor($workspace, $user)) {
            return new JsonResponse([
                'statusCode' => Http::STATUS_NOT_FOUND,
                'message' => 'Workspace not found',
            ], Http::STATUS_NOT_FOUND);
        }

        // TODO(workspace-ownership): Decide how to handle workspaces when their owning
        // account is deleted. Currently a missing owner treats the workspace as not found (404).
        // Options to investigate: soft-delete ownership, block account deletion while referenced,
        // or expose a placeholder owner (e.g. "Deleted account").
        try {
            $account = $this->accountRepository->findOneById($workspace->accountId);
        } catch (EmptyResultException) {
            return new JsonResponse([
                'statusCode' => Http::STATUS_NOT_FOUND,
                'message' => 'Workspace not found',
            ], Http::STATUS_NOT_FOUND);
        }

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
            ],
        );

        return new JsonResponse($response, Http::STATUS_OK);
    }
}
