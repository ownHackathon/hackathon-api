<?php declare(strict_types=1);

namespace ownHackathon\Core\Token;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use ownHackathon\Core\Shared\Infrastructure\Hydrator\Token\TokenHydratorInterface;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use ownHackathon\Core\Shared\Infrastructure\Persistence\Store\Token\TokenStoreInterface;
use ownHackathon\Core\Shared\Utils\UuidFactoryInterface;
use ownHackathon\Core\Token\Infrastructure\Hydrator\TokenHydrator;
use ownHackathon\Core\Token\Infrastructure\Persistence\Repository\TokenRepository;
use ownHackathon\Core\Token\Infrastructure\Persistence\Table\TokenTable;

readonly class ConfigProvider
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
        return [
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                TokenHydratorInterface::class => TokenHydrator::class,
                TokenRepositoryInterface::class => TokenRepository::class,
                TokenStoreInterface::class => TokenTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                TokenHydrator::class => ConfigAbstractFactory::class,
                TokenRepository::class => ConfigAbstractFactory::class,
                TokenTable::class => ConfigAbstractFactory::class,
            ],

        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            TokenHydrator::class => [
                UuidFactoryInterface::class,
            ],
            TokenRepository::class => [
                TokenStoreInterface::class,
                TokenHydratorInterface::class,
            ],
            TokenTable::class => [
                Query::class,
            ],
        ];
    }
}
