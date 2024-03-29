<?php declare(strict_types=1);

namespace Test\Unit\Authentication\Service;

use App\Service\Authentication\ApiAccessService;
use Test\Unit\App\Service\AbstractService;

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
        self::assertSame(true, $hasRights);
    }

    public function testHasAccessNotRights(): void
    {
        $hasRights = $this->apiAccessService->hasAccessRights('example.com');
        self::assertSame(false, $hasRights);
    }
}
