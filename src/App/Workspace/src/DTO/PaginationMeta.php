<?php declare(strict_types=1);

namespace ownHackathon\Workspace\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Pagination Meta',
    description: 'Info about the number of pages and limits'
)]
readonly class PaginationMeta
{
    public function __construct(
        #[OA\Property(example: 1)]
        public int $currentPage,
        #[OA\Property(example: 10)]
        public int $totalPages,
        #[OA\Property(example: 100)]
        public int $totalItems,
    ) {
    }

    public static function fromValues(int $totalCount, int $totalPages, int $currentPage): self
    {
        return new self($currentPage, $totalPages, $totalCount);
    }
}
