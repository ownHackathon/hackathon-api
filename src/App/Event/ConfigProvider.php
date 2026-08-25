<?php declare(strict_types=1);

namespace ownHackathon\Event;

use Envms\FluentPDO\Query;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use ownHackathon\Event\Infrastructure\Hydrator\EventHydrator;
use ownHackathon\Event\Infrastructure\Persistence\Repository\EventRepository;
use ownHackathon\Event\Infrastructure\Persistence\Table\EventTable;
use ownHackathon\Shared\Infrastructure\Hydrator\EventHydratorInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Repository\EventRepositoryInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Table\EventStoreInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'routes' => $this->getRoutes(),
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getRoutes(): array
    {
        return [];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                EventHydratorInterface::class => EventHydrator::class,
                EventStoreInterface::class => EventTable::class,
                EventRepositoryInterface::class => EventRepository::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                EventHydrator::class => ConfigAbstractFactory::class,
                EventTable::class => ConfigAbstractFactory::class,
                EventRepositoryInterface::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            EventHydrator::class => [
                UuidFactoryInterface::class,
            ],
            EventTable::class => [
                Query::class,
            ],
            EventRepository::class => [
                EventStoreInterface::class,
                EventHydratorInterface::class,
            ]
        ];
    }

}
