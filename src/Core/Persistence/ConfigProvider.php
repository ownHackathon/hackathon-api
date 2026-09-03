<?php declare(strict_types=1);

namespace Core\Persistence;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Core\Persistence\Middleware\FluentTransactionMiddleware;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    FluentTransactionMiddleware::class => ConfigAbstractFactory::class,
                ],
            ],
            ConfigAbstractFactory::class => [
                FluentTransactionMiddleware::class => [Query::class],
            ],
        ];
    }
}
