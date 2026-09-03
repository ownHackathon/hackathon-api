<?php declare(strict_types=1);

namespace App\Token;

use App\Token\Application\Port\TokenLoggerInterface;
use App\Token\Domain\Repository\TokenRepositoryInterface;
use App\Token\Infrastructure\Factory\TokenLoggerFactory;
use App\Token\Infrastructure\Hydrator\TokenHydrator;
use App\Token\Infrastructure\Hydrator\TokenHydratorInterface;
use App\Token\Infrastructure\Persistence\Repository\TokenRepository;
use App\Token\Infrastructure\Persistence\Table\TokenStoreInterface;
use App\Token\Infrastructure\Persistence\Table\TokenTable;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;

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
                TokenLoggerInterface::class => TokenLoggerFactory::class,
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
                TokenLoggerInterface::class,
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
