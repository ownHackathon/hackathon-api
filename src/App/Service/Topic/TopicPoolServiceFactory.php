<?php declare(strict_types=1);

namespace App\Service\Topic;

use App\Hydrator\ReflectionHydrator;
use App\Repository\TopicPoolRepository;
use App\System\Hydrator\Strategy\UuidStrategy;
use Psr\Container\ContainerInterface;

class TopicPoolServiceFactory
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
