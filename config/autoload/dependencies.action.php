<?php declare(strict_types=1);

use App\Account\Identity\Application\Port\ActivityLoggerInterface;
use Core\Persistence\Factory\DatabaseFactory;
use Core\Persistence\Factory\QueryFactory;
use Core\SharedKernel\Factory\UuidFactory;
use Core\SharedKernel\Utils\UuidFactoryInterface;
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
            ActivityLoggerInterface::class => ArrayLogger::class,
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
