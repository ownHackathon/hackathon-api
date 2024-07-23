<?php declare(strict_types=1);

namespace Test\Unit\Mock\Service;

use Core\Authentication\Service\ApiAccessService;

readonly class MockApiAccessService extends ApiAccessService
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
