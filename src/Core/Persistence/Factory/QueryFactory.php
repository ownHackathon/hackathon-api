<?php declare(strict_types=1);

namespace Core\Persistence\Factory;

use Envms\FluentPDO\Query;
use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class QueryFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Query
    {
        return new Query($container->get(PDO::class));
    }
}
