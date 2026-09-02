<?php declare(strict_types=1);

namespace ownHackathon\Core\Persistence;

readonly final class Pagination
{
    public const int MIN_PAGE = 1;
    public const int DEFAULT_LIMIT = 5;
    public const int MAX_LIMIT = 250;

    public function __construct(
        public int $page,
        public int $limit,
        public int $offset,
    ) {
    }

    public static function fromParams(array $params, int $defaultLimit = self::DEFAULT_LIMIT): self
    {
        $page = max(self::MIN_PAGE, (int)($params['page'] ?? self::MIN_PAGE));
        $limit = max(self::MIN_PAGE, min(self::MAX_LIMIT, (int)($params['limit'] ?? $defaultLimit)));

        $offset = ($page - 1) * $limit;

        return new self($page, $limit, $offset);
    }
}
