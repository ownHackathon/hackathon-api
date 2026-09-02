<?php declare(strict_types=1);

use ownHackathon\Core\Persistence\Factory\DatabaseFactory;
use ownHackathon\Core\Persistence\Factory\QueryFactory;
use ownHackathon\Core\SharedKernel\Factory\UuidFactory;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
use Tests\Integration\Mock\NullLoggerFactory;
use Tests\Integration\Mock\NullMailerFactory;
use Tests\Integration\Mock\ArrayLogger;

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
            'logger.account-activity' => ArrayLogger::class,
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
