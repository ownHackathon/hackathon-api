<?php declare(strict_types=1);

namespace App\Service;

use App\Table\ProjectTable;
use App\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class ProjectServiceFactory
{
    public function __invoke(ContainerInterface $container): ProjectService
    {
        $table = $container->get(ProjectTable::class);
        $hydrator = $container->get(ReflectionHydrator::class);
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'createTime',
            $strategy,
        );

        return new ProjectService($table, $hydrator);
    }
}
