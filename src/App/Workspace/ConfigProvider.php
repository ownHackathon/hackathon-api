<?php declare(strict_types=1);

namespace ownHackathon\Workspace;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use ownHackathon\Workspace\Infrastructure\Hydrator\WorkspaceHydrator;
use ownHackathon\Workspace\Infrastructure\Hydrator\WorkspaceHydratorInterface;
use ownHackathon\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository;
use ownHackathon\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Workspace\Infrastructure\Persistence\Table\WorkspaceStoreInterface;
use ownHackathon\Workspace\Infrastructure\Persistence\Table\WorkspaceTable;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                WorkspaceHydratorInterface::class => WorkspaceHydrator::class,
                WorkspaceRepositoryInterface::class => WorkspaceRepository::class,
                WorkspaceStoreInterface::class => WorkspaceTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                WorkspaceHydrator::class => InvokableFactory::class,
                WorkspaceRepository::class => ConfigAbstractFactory::class,
                WorkspaceTable::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            WorkspaceRepository::class => [
                WorkspaceStoreInterface::class,
            ],
            WorkspaceTable::class => [
                Query::class,
                WorkspaceHydratorInterface::class,
            ],
        ];
    }
}
