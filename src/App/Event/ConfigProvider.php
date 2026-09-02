<?php declare(strict_types=1);

namespace ownHackathon\App\Event;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use ownHackathon\App\Event\Domain\Repository\EventRepositoryInterface;
use ownHackathon\App\Event\Infrastructure\Hydrator\EventHydrator;
use ownHackathon\App\Event\Infrastructure\Hydrator\EventHydratorInterface;
use ownHackathon\App\Event\Infrastructure\Persistence\Repository\EventRepository;
use ownHackathon\App\Event\Infrastructure\Persistence\Table\EventStoreInterface;
use ownHackathon\App\Event\Infrastructure\Persistence\Table\EventTable;
use ownHackathon\Core\Observability\ChannelLoggerFactory;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;

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
                'logger.event' => ChannelLoggerFactory::class,
                EventHydrator::class => ConfigAbstractFactory::class,
                EventTable::class => ConfigAbstractFactory::class,
                EventRepository::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            EventHydrator::class => [
                UuidFactoryInterface::class,
                'logger.event',
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
