<?php

declare(strict_types=1);

use Exdrals\Core\Shared\Infrastructure\Factory\DatabaseFactory;
use Exdrals\Core\Shared\Infrastructure\Factory\QueryFactory;
use Exdrals\Core\Shared\Infrastructure\Factory\UuidFactory;
use Exdrals\Core\Shared\Infrastructure\Logger\LoggerFactory;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Tests\Integration\Mock\NullMailerFactory;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            PDO::class => 'database',
            Envms\FluentPDO\Query::class => 'query',
            Psr\Log\LoggerInterface::class => 'logger',
            UuidFactoryInterface::class => 'uuid',
            \Symfony\Component\Mailer\MailerInterface::class => 'mailer',
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            'database' => DatabaseFactory::class,
            'query' => QueryFactory::class,
            'logger' => LoggerFactory::class,
            'uuid' => UuidFactory::class,
            'mailer' => NullMailerFactory::class,
        ],
        'delegators' => [
            Application::class => [
                ApplicationConfigInjectionDelegator::class,
            ],
        ],
    ],
];
