<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Entity\Event;
use App\Hydrator\ReflectionHydrator;
use App\Service\Event\EventService;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use Psr\Container\ContainerInterface;

readonly class EventCreateMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): EventCreateMiddleware
    {
        /** @var EventService $service */
        $service = $container->get(EventService::class);

        /** @var ReflectionHydrator $hydrator */
        $hydrator = clone $container->get(ReflectionHydrator::class);

        /** @var DateTimeFormatterStrategy $strategy */
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'createdAt',
            $strategy,
        );

        $hydrator->addStrategy(
            'startedAt',
            $strategy,
        );

        $hydrator->addStrategy(
            'event',
            new HydratorStrategy($container->get(ReflectionHydrator::class), Event::class)
        );

        return new EventCreateMiddleware($service, $hydrator);
    }
}
