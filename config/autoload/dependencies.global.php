<?php declare(strict_types=1);

return [
    'dependencies' => [
        'aliases' => [
            PDO::class => 'database',
            Envms\FluentPDO\Query::class => 'query',
            Mustache_Engine::class => 'mustache',
        ],
        'invokables' => [
        ],
        'factories' => [
            'database' => Administration\Factory\DatabaseFactory::class,
            'query' => Administration\Factory\QueryFactory::class,
            'mustache' => Administration\Factory\MustacheFactory::class,
        ],
    ],
];
