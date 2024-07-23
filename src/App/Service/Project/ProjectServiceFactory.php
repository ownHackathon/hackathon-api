<?php declare(strict_types=1);

namespace App\Service\Project;

use App\Hydrator\ReflectionHydrator;
use App\Hydrator\Strategy\UuidStrategy;
use App\Repository\ProjectRepository;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

readonly class ProjectServiceFactory
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
            'createdAt',
            $strategy,
        );

        $hydrator->addStrategy(
            'uuid',
            new UuidStrategy()
        );

        return new ProjectService($repository, $hydrator);
    }
}
