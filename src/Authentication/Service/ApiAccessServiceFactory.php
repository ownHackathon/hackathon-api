<?php declare(strict_types=1);

namespace Authentication\Service;

use Psr\Container\ContainerInterface;

class ApiAccessServiceFactory
{
    public function __invoke(ContainerInterface $container): ApiAccessService
    {
        $apiAccessConfig = $container->get('config')['api']['access'];

        return new ApiAccessService($apiAccessConfig);
    }
}
