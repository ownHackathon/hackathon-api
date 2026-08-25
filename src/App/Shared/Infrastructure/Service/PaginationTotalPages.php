<?php declare(strict_types=1);

namespace ownHackathon\Shared\Infrastructure\Service;

readonly class PaginationTotalPages
{
    public function getTotalPages(int $totalItemCount, int $itemPerPage): int
    {
        return max(1, (int)ceil($totalItemCount / $itemPerPage));
    }
}
