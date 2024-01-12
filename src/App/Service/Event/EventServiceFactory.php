<?php declare(strict_types=1);

namespace App\Service\Event;

use App\Enum\EventStatus;
use App\Hydrator\ReflectionHydrator;
use App\Repository\EventRepository;
use Laminas\Hydrator\Strategy\BackedEnumStrategy;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class EventServiceFactory
{
    public function __invoke(ContainerInterface $container): EventService
    {
        $repository = $container->get(EventRepository::class);
        $hydrator = clone $container->get(ReflectionHydrator::class);
        $strategy = $container->get(DateTimeFormatterStrategy::class);

        $hydrator->addStrategy(
            'createTime',
            $strategy,
        );
        $hydrator->addStrategy(
            'startTime',
            $strategy,
        );
        $hydrator->addStrategy(
            'status',
            new BackedEnumStrategy(EventStatus::class)
        );

        return new EventService($repository, $hydrator);
    }
}
