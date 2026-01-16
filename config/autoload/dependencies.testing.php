<?php declare(strict_types=1);

use Core\Factory\DatabaseFactory;
use Core\Factory\QueryFactory;
use Core\Factory\UuidFactory;
use Core\Logger\LoggerFactory;
use Core\Utils\UuidFactoryInterface;
use FunctionalTest\Mock\NullMailerFactory;

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
    ],
];
