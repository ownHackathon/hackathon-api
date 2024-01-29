<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\Project;
use App\Hydrator\ReflectionHydrator;
use App\Repository\ProjectRepository;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use Psr\Container\ContainerInterface;

class ProjectServiceFactory
{
    public function __invoke(ContainerInterface $container): ProjectService
    {
        /** @var ProjectRepository $repository */
        $repository = $container->get(ProjectRepository::class);

        /** @var ReflectionHydrator $hydrator */
        $hydrator = clone $container->get(ReflectionHydrator::class);

        /** @var DateTimeFormatterStrategy $strategy */
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'createTime',
            $strategy,
        );

        $hydrator->addStrategy(
            'project',
            new HydratorStrategy($container->get(ReflectionHydrator::class), Project::class)
        );

        /** ToDo implements Uuid Strategy */

        return new ProjectService($repository, $hydrator);
    }
}
