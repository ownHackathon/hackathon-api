<?php declare(strict_types=1);

return [
    'table_storage' => [
        'table_name' => 'MigrationVersions',
        'version_column_name' => 'version',
        'version_column_length' => 1024,
        'executed_at_column_name' => 'executedAt',
        'execution_time_column_name' => 'executionTime',
    ],

    'migrations_paths' => [
        'Migrations' => __DIR__ . '/../../database/migrations',
    ],

    'all_or_nothing' => true,
    'transactional' => true,
    'check_database_platform' => true,
    'organize_migrations' => 'none',
];
