<?php declare(strict_types=1);

namespace Core\Service;

readonly class ApiAccessService
{
    public function __construct(
        private array $apiAccessConfig,
    ) {
    }

    public function hasAccessRights(string $domain): bool
    {
        return in_array($domain, $this->apiAccessConfig['domain']['whitelist'], true);
    }
}
