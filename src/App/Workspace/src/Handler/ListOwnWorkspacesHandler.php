<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Handler;

use Exdrals\Identity\DTO\Response\HttpResponseMessage;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\Shared\Domain\ValueObject\Pagination;
use ownHackathon\Workspace\DTO\WorkspaceList;
use ownHackathon\Workspace\DTO\WorkspaceResponse;
use ownHackathon\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use ownHackathon\Workspace\Infrastructure\Service\PaginationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ListOwnWorkspacesHandler implements RequestHandlerInterface
{
    public function __construct(
        private WorkspaceRepository $workspaceRepository,
        private PaginationService $paginationService,
    ) {
    }

    #[OA\Get(
        path: '/me/workspaces',
        operationId: 'findWorkspaces',
        description: 'List all workspaces for the authenticated account',
        summary: 'Returns a collection of all workspaces owned by or associated with the currently authenticated user.',
        security: [['accessToken' => []]],
        tags: ['Workspace'],
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'The page number to retrieve.',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'integer',
            default: 1,
            minimum: 1
        )
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'The number of items to return per page.',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'integer',
            default: 25,
            maximum: 250,
            minimum: 1
        )
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
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);
        $pagination = $request->getAttribute(Pagination::class);

        $metaData = $this->paginationService->getMetaDataByAccountId($pagination, $account->id);

        $workspaces = [];
        if ($metaData->totalItems > 0 && $pagination->page <= $metaData->totalPages) {
            $workspaces = $this->workspaceRepository->findByAccountId($account->id, $pagination);
        }

        $response = [];
        foreach ($workspaces as $workspace) {
            $response[] = WorkspaceResponse::fromEntity($workspace, $account);
        }

        return new JsonResponse([
            'workspaces' => $response,
            'meta' => [
                'currentPage' => $metaData->currentPage,
                'maxPages' => $metaData->totalPages,
                'totalItems' => $metaData->totalItems,
            ],
        ], HTTP::STATUS_OK);
    }
}
