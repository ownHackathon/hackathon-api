<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Service;

use ownHackathon\Shared\Domain\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Shared\Domain\ValueObject\Pagination;
use ownHackathon\Workspace\DTO\PaginationMeta;

readonly class PaginationService
{
    public function __construct(
        private WorkspaceRepositoryInterface $workspaceRepository,
    ) {
    }

    public function getMetaDataByAccountId(Pagination $pagination, int $accountId): PaginationMeta
    {
        $totalCount = $this->workspaceRepository->countByAccount($accountId);
        $totalPages = (int)ceil($totalCount / $pagination->limit);
        $totalPages = max(1, $totalPages);

        return PaginationMeta::fromValues($totalCount, $totalPages, $pagination->page);
    }
}
