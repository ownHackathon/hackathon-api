<?php

namespace App\Middleware\Event;

use App\Hydrator\ReflectionHydrator;
use App\Service\EventService;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class EventCreateMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): EventCreateMiddleware
    {
        $service = $container->get(EventService::class);
        $hydrator = $container->get(ReflectionHydrator::class);
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'createTime',
            $strategy,
        );
        $hydrator->addStrategy(
            'startTime',
            $strategy,
        );

        return new EventCreateMiddleware($service, $hydrator);
    }
}
