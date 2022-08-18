<?php declare(strict_types=1);

namespace App\Factory;

use Envms\FluentPDO\Query;
use PDO;
use Psr\Container\ContainerInterface;

class QueryFactory
{
    public function __invoke(ContainerInterface $container): Query
    {
        return new Query($container->get(PDO::class));
    }
}
