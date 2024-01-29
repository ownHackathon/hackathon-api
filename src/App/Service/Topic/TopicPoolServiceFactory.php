<?php declare(strict_types=1);

namespace App\Service\Topic;

use App\Entity\Topic;
use App\Hydrator\ReflectionHydrator;
use App\Repository\TopicPoolRepository;
use Laminas\Hydrator\Strategy\HydratorStrategy;
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
            'topic',
            new HydratorStrategy($container->get(ReflectionHydrator::class), Topic::class)
        );

        /** ToDo implements Uuid Strategy */

        return new TopicPoolService($repository, $hydrator);
    }
}
