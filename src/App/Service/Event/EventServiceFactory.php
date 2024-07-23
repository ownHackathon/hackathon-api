<?php declare(strict_types=1);

namespace App\Service\Event;

use App\Enum\EventStatus;
use App\Hydrator\ReflectionHydrator;
use App\Hydrator\Strategy\UuidStrategy;
use App\Repository\EventRepository;
use Laminas\Hydrator\Strategy\BackedEnumStrategy;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

readonly class EventServiceFactory
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
            'createdAt',
            $strategy,
        );

        $hydrator->addStrategy(
            'startedAt',
            $strategy,
        );

        $hydrator->addStrategy(
            'status',
            new BackedEnumStrategy(EventStatus::class)
        );

        $hydrator->addStrategy(
            'uuid',
            new UuidStrategy()
        );

        return new EventService($repository, $hydrator);
    }
}
