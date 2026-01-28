<?php declare(strict_types=1);

namespace ownHackathon\Shared\Domain\ValueObject;

readonly class Pagination
{
    public function __construct(
        public int $page,
        public int $limit,
        public int $offset
    ) {
    }

    public static function fromParams(array $params, int $defaultLimit = 25): self
    {
        $page = max(1, (int)($params['page'] ?? 1));
        $limit = max(1, min(250, (int)($params['limit'] ?? $defaultLimit)));
        $offset = ($page - 1) * $limit;

        return new self($page, $limit, $offset);
    }
}
