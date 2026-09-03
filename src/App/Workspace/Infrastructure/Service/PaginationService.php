<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Service;

use App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use App\Workspace\DTO\PaginationMeta;
use Core\Persistence\Pagination;

readonly final class PaginationService
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
