<?php declare(strict_types=1);

namespace App\Service;

use App\Table\EventTable;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class EventServiceFactory
{
    public function __invoke(ContainerInterface $container): EventService
    {
        $table = $container->get(EventTable::class);
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

        return new EventService($table, $hydrator);
    }
}
