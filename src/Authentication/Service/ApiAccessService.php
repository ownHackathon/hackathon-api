<?php declare(strict_types=1);

namespace Authentication\Service;

use Psr\Http\Message\ServerRequestInterface;

class ApiAccessService
{
    public function __construct(
        private readonly array $apiAccessConfig,
    ) {
    }

    public function hasAccessRights(string $domain): bool
    {
        return in_array($domain, $this->apiAccessConfig['domain']['whitelist']);
    }
}
