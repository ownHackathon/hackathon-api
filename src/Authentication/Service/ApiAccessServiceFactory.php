<?php declare(strict_types=1);

namespace Authentication\Service;

use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiAccessServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ApiAccessService
    {
        $apiAccessConfig = $container->get('config')['api']['access'];

        return new ApiAccessService($apiAccessConfig);
    }
}
