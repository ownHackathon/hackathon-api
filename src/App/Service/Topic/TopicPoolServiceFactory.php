<?php declare(strict_types=1);

namespace App\Service\Topic;

use App\Repository\TopicPoolRepository;
use Core\Hydrator\ReflectionHydrator;
use Core\Hydrator\Strategy\UuidStrategy;
use Psr\Container\ContainerInterface;

readonly class TopicPoolServiceFactory
{
    public function __invoke(ContainerInterface $container): TopicPoolService
    {
        /** @var TopicPoolRepository $repository */
        $repository = $container->get(TopicPoolRepository::class);

        /** @var ReflectionHydrator $hydrator */
        $hydrator = clone $container->get(ReflectionHydrator::class);

        $hydrator->addStrategy(
            'uuid',
            new UuidStrategy()
        );

        return new TopicPoolService($repository, $hydrator);
    }
}
