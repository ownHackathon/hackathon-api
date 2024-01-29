<?php declare(strict_types=1);

namespace App\Service\Event;

use App\Entity\Event;
use App\Enum\EventStatus;
use App\Hydrator\ReflectionHydrator;
use App\Repository\EventRepository;
use App\System\Hydrator\Strategy\UuidStrategy;
use Laminas\Hydrator\Strategy\BackedEnumStrategy;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\HydratorStrategy;
use Psr\Container\ContainerInterface;

class EventServiceFactory
{
    public function __invoke(ContainerInterface $container): EventService
    {
        /** @var EventRepository $repository */
        $repository = $container->get(EventRepository::class);

        /** @var ReflectionHydrator $hydrator */
        $hydrator = clone $container->get(ReflectionHydrator::class);

        /** @var DateTimeFormatterStrategy $strategy */
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

        $hydrator->addStrategy(
            'event',
            new HydratorStrategy($container->get(ReflectionHydrator::class), Event::class)
        );

        $hydrator->addStrategy(
            'uuid',
            new UuidStrategy()
        );

        return new EventService($repository, $hydrator);
    }
}
