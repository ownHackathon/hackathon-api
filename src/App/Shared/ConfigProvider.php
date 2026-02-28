<?php declare(strict_types=1);

namespace ownHackathon\Shared;

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use ownHackathon\Shared\Infrastructure\Service\PaginationTotalPages;
use ownHackathon\Shared\Middleware\PaginationMiddleware;
use ownHackathon\Shared\Validator\Input\VisibilityInput;

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

            ],
            'invokables' => [
                VisibilityInput::class,
            ],
            'factories' => [
                PaginationMiddleware::class => InvokableFactory::class,
                PaginationTotalPages::class => InvokableFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [];
    }

}
