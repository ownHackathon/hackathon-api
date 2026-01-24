<?php declare(strict_types=1);

use FunctionalTest\Mock\NullMailerFactory;
use Shared\Infrastructure\Factory\DatabaseFactory;
use Shared\Infrastructure\Factory\QueryFactory;
use Shared\Infrastructure\Factory\UuidFactory;
use Shared\Infrastructure\Logger\LoggerFactory;
use Shared\Utils\UuidFactoryInterface;

return [
    'dependencies' => [
        'aliases' => [
            PDO::class => 'database',
            Envms\FluentPDO\Query::class => 'query',
            UuidFactoryInterface::class => 'uuid',
            Psr\Log\LoggerInterface::class => 'logger',
            Symfony\Component\Mailer\MailerInterface::class => 'mailer',
        ],
        'invokables' => [
        ],
        'factories' => [
            'database' => DatabaseFactory::class,
            'query' => QueryFactory::class,
            'logger' => LoggerFactory::class,
            'uuid' => UuidFactory::class,
            'mailer' => NullMailerFactory::class,
        ],
        'delegators' => [
            \Mezzio\Application::class => [
                \Mezzio\Container\ApplicationConfigInjectionDelegator::class,
            ],
        ],
    ],
];
