<?php declare(strict_types=1);

use Exdrals\Shared\Infrastructure\Factory\DatabaseFactory;
use Exdrals\Shared\Infrastructure\Factory\QueryFactory;
use Exdrals\Shared\Infrastructure\Factory\UuidFactory;
use Exdrals\Shared\Utils\UuidFactoryInterface;
use Tests\Integration\Mock\NullMailerFactory;
use Tests\Integration\Mock\NullLoggerFactory;

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
            'logger' => NullLoggerFactory::class,
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
