<?php declare(strict_types=1);

namespace ownHackathon\Workspace\DTO;

readonly class PaginationMeta
{
    public function __construct(
        public int $totalItems,
        public int $totalPages,
        public int $currentPage,
    ) {
    }

    public static function fromValues(int $totalCount, int $totalPages, int $currentPage): self
    {
        return new self($totalCount, $totalPages, $currentPage);
    }
}
