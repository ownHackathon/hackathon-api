<?php declare(strict_types=1);

namespace App;

use App\Workspace\Infrastructure\Hydrator\Workspace\WorkspaceHydratorInterface;
use App\Workspace\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use App\Workspace\Infrastructure\Persistence\Table\WorkspaceTable;
use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;

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
                Workspace\Infrastructure\Hydrator\Workspace\WorkspaceHydratorInterface::class => Workspace\Infrastructure\Hydrator\Workspace\WorkspaceHydrator::class,
                WorkspaceRepositoryInterface::class => Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository::class,
                Workspace\Infrastructure\Persistence\Table\WorkspaceStoreInterface::class => WorkspaceTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                Workspace\Infrastructure\Hydrator\Workspace\WorkspaceHydrator::class => InvokableFactory::class,
                Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository::class => ConfigAbstractFactory::class,
                Workspace\Infrastructure\Persistence\Table\WorkspaceTable::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Workspace\Infrastructure\Persistence\Repository\WorkspaceRepository::class => [
                Workspace\Infrastructure\Persistence\Table\WorkspaceStoreInterface::class,
            ],
            Workspace\Infrastructure\Persistence\Table\WorkspaceTable::class => [
                Query::class,
                WorkspaceHydratorInterface::class,
            ],
        ];
    }
}
