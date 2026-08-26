<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Infrastructure\Service;

use ownHackathon\App\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\App\Shared\Infrastructure\Service\PaginationTotalPages;
use ownHackathon\App\Shared\Infrastructure\ValueObject\Pagination;
use ownHackathon\App\Workspace\DTO\PaginationMeta;

readonly class PaginationService
{
    public function __construct(
        private WorkspaceRepositoryInterface $repository,
        private PaginationTotalPages $pages,
    ) {
    }

    public function getMetaDataByAccountId(Pagination $pagination, int $accountId): PaginationMeta
    {
        $totalCount = $this->repository->countByAccount($accountId);
        $totalPages = $this->pages->getTotalPages($totalCount, $pagination->limit);

        return PaginationMeta::fromValues($totalCount, $totalPages, $pagination->page);
    }
}
