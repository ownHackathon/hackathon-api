<?php declare(strict_types=1);

namespace Authentication\Test\Mock\Service;

use Authentication\Service\ApiAccessService;

class MockApiAccessService extends ApiAccessService
{
    public function __construct()
    {
        parent::__construct([]);
    }

    public function hasAccessRights(string $domain): bool
    {
        return $domain === 'localhost';
    }
}
