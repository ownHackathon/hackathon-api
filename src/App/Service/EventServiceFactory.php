<?php declare(strict_types=1);

namespace App\Service;

use App\Enum\EventStatus;
use App\Hydrator\ReflectionHydrator;
use App\Table\EventTable;
use Laminas\Hydrator\Strategy\BackedEnumStrategy;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class EventServiceFactory
{
    public function __invoke(ContainerInterface $container): EventService
    {
        $table = $container->get(EventTable::class);
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

        return new EventService($table, $hydrator);
    }
}
