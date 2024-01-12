<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Hydrator\ReflectionHydrator;
use App\Repository\ProjectRepository;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class ProjectServiceFactory
{
    public function __invoke(ContainerInterface $container): ProjectService
    {
        $repository = $container->get(ProjectRepository::class);
        $hydrator = $container->get(ReflectionHydrator::class);
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'createTime',
            $strategy,
        );

        return new ProjectService($repository, $hydrator);
    }
}
