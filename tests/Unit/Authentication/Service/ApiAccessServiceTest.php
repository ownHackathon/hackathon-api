<?php declare(strict_types=1);

namespace Test\Unit\Authentication\Service;

use Test\Unit\App\Service\AbstractService;
use Authentication\Service\ApiAccessService;

class ApiAccessServiceTest extends AbstractService
{
    private array $config;
    private ApiAccessService $apiAccessService;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = [
            'domain' => [
                'whitelist' => [
                    'localhost',
                ],
            ],
        ];

        $this->apiAccessService = new ApiAccessService($this->config);
    }

    public function testHasAccessRights(): void
    {
        $hasRights = $this->apiAccessService->hasAccessRights('localhost');
        $this->assertSame(true, $hasRights);
    }

    public function testHasAccessNotRights(): void
    {
        $hasRights = $this->apiAccessService->hasAccessRights('example.com');
        $this->assertSame(false, $hasRights);
    }
}
