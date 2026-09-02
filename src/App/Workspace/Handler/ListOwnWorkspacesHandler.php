<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Handler;

use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Workspace\DTO\PaginationMeta;
use ownHackathon\App\Workspace\DTO\WorkspaceResponse;
use ownHackathon\App\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use ownHackathon\App\Workspace\Infrastructure\Service\PaginationService;
use ownHackathon\Core\Http\DTO\HttpResponseMessage;
use ownHackathon\Core\Persistence\Pagination;
use ownHackathon\Core\Serialization\DataType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class ListOwnWorkspacesHandler implements RequestHandlerInterface
{
    public function __construct(
        private WorkspaceRepository $repository,
        private PaginationService $service,
    ) {
    }

    #[OA\Get(
        path: '/me/workspaces',
        operationId: 'findOwnWorkspaces',
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
            default: Pagination::MIN_PAGE,
            minimum: Pagination::MIN_PAGE,
        ),
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'The number of items to return per page.',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'integer',
            default: Pagination::DEFAULT_LIMIT,
            maximum: Pagination::MAX_LIMIT,
            minimum: Pagination::MIN_PAGE,
        ),
    )]
    #[OA\Response(
        response: Http::STATUS_OK,
        description: 'A list of workspaces belonging to the user.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'workspaces',
                    type: 'array',
                    items: new OA\Items(ref: WorkspaceResponse::class),
                ),
                new OA\Property(
                    property: 'meta',
                    ref: PaginationMeta::class,
                    type: DataType::OBJECT->value,
                ),
            ],
            type: DataType::OBJECT->value,
        ),
    )]
    #[OA\Response(
        response: Http::STATUS_UNAUTHORIZED,
        description: 'Authentication failed. The access token is missing or invalid.',
        content: new OA\JsonContent(ref: HttpResponseMessage::class),
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);
        $pagination = $request->getAttribute(Pagination::class);

        assert($account instanceof AccountInterface);
        assert($pagination instanceof Pagination);

        $metaData = $this->service->getMetaDataByAccountId($pagination, $account->id);

        $response = [];
        if ($metaData->totalItems > 0 && $pagination->page <= $metaData->totalPages) {
            $workspaces = $this->repository->findByAccountId($account->id, $pagination);
            foreach ($workspaces as $workspace) {
                $response[] = WorkspaceResponse::fromEntity($workspace, $account);
            }
        }

        return new JsonResponse([
            'workspaces' => $response,
            'meta' => [
                'currentPage' => $metaData->currentPage,
                'totalPages' => $metaData->totalPages,
                'totalItems' => $metaData->totalItems,
            ],
        ], Http::STATUS_OK);
    }
}
