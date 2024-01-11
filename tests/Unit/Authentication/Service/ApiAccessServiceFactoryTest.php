<?php declare(strict_types=1);

namespace Test\Unit\Authentication\Service;

use Authentication\Service\ApiAccessService;
use Authentication\Service\ApiAccessServiceFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Test\Unit\App\Service\AbstractService;
use Test\Unit\Mock\MockContainer;

class ApiAccessServiceFactoryTest extends AbstractService
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCanCreateApiAccessService(): void
    {
        $config = [
            'api' => [
                'access' => [
                    'domain' => [
                        'whitelist' => [
                            'localhost',
                        ],
                    ],
                ],
            ],
        ];

        $container = new MockContainer(['config' => $config]);

        $apiAccessService = (new ApiAccessServiceFactory())($container);

        self::assertInstanceOf(ApiAccessService::class, $apiAccessService);
    }
}
