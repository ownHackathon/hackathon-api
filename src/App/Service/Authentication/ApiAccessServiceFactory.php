<?php declare(strict_types=1);

namespace App\Service\Authentication;

use Psr\Container\ContainerInterface;

class ApiAccessServiceFactory
{
    public function __invoke(ContainerInterface $container): ApiAccessService
    {
        $apiAccessConfig = $container->get('config')['api']['access'];

        return new ApiAccessService($apiAccessConfig);
    }
}
