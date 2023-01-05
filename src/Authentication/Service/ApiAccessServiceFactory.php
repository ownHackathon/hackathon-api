<?php declare(strict_types=1);

namespace Authentication\Service;

use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiAccessServiceFactory
{
    public function __invoke(ContainerInterface $container): ApiAccessService
    {
        $apiAccessConfig = $container->get('config')['api']['access'];

        return new ApiAccessService($apiAccessConfig);
    }
}
