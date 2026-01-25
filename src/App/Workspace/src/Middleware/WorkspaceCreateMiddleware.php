<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Middleware;

use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Shared\Domain\Exception\HttpDuplicateEntryException;
use Exdrals\Shared\Infrastructure\Service\SlugService;
use ownHackathon\Workspace\Domain\Message\WorkspaceLogMessage;
use ownHackathon\Workspace\Domain\Message\WorkspaceStatusMessage;
use ownHackathon\Workspace\Domain\Workspace;
use ownHackathon\Workspace\DTO\WorkspaceRequest;
use ownHackathon\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class WorkspaceCreateMiddleware implements MiddlewareInterface
{
    public function __construct(
        private WorkspaceRepositoryInterface $repository,
        private SlugService $slugService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var WorkspaceRequest $workspace */
        $workspace = $request->getAttribute(WorkspaceRequest::class);

        /** @var AccountInterface $account */
        $account = $request->getAttribute(AccountInterface::AUTHENTICATED);

        $slug = $this->slugService->getSlugFromString($workspace->workspaceName);
        $workspace = new Workspace(
            id: null,
            accountId: $account->id,
            name: $workspace->workspaceName,
            slug: $slug,
        );

        try {
            $this->repository->insert($workspace);
        } catch (DuplicateEntryException $exception) {
            throw new HttpDuplicateEntryException(
                WorkspaceLogMessage::DUPLICATED_WORKSPACE_NAME,
                WorkspaceStatusMessage::DUPLICATED_WORKSPACE_NAME,
                [
                    'Workspace:' => $workspace->name,
                ]
            );
        }

        return $handler->handle($request->withAttribute(Workspace::class, $workspace));
    }
}
