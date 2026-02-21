<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Handler;

use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\JsonResponse;
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

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $slug = $request->getAttribute('slug');

        $workspace = $this->workspaceRepository->findOneBySlug($slug);
        $account = $this->accountRepository->findOneById($workspace->accountId);

        $response = Workspace::fromArray(
            [
                'name' => $workspace->name,
                'description' => $workspace->description,
                'owner' => $account->name,
                'ownerUuid' => $account->uuid->toString(),
            ]
        );

        return new JsonResponse($response, Http::STATUS_OK);
    }
}
