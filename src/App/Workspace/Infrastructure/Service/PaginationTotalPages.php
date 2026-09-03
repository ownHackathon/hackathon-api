<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Service;

readonly final class PaginationTotalPages
{
    public function getTotalPages(int $totalItemCount, int $itemPerPage): int
    {
        return max(1, (int)ceil($totalItemCount / $itemPerPage));
    }
}
