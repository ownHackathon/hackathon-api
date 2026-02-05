#!/usr/bin/env php
<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\Migrations\Tools\Console\Command;
use Symfony\Component\Console\Application;

$env = getenv('APP_ENV') ?? 'global';

$dbParams = (require realpath(__DIR__) . sprintf('/../config/autoload/database.%s.php', $env))['database'];
$dbParams['driver'] = 'pdo_' . $dbParams['driver'];

$config = (require realpath(__DIR__) . sprintf('/../config/autoload/migrations.%s.php', $env))['migrations'];

$connection = DriverManager::getConnection($dbParams);

$configuration = new Configuration();

$configuration->setCustomTemplate(__DIR__ . '/../config/migrations.template.tpl');

foreach ($config['migrations_paths'] as $key => $path) {
  $configuration->addMigrationsDirectory($key, $path);
}

$configuration->setAllOrNothing($config['all_or_nothing']);
$configuration->setCheckDatabasePlatform($config['check_database_platform']);
$configuration->setTransactional($config['transactional']);
$configuration->setMigrationOrganization($config['organize_migrations']);

$storageConfiguration = new TableMetadataStorageConfiguration();
$storageConfiguration->setTableName($config['table_storage']['table_name']);
$storageConfiguration->setVersionColumnName($config['table_storage']['version_column_name']);
$storageConfiguration->setVersionColumnLength($config['table_storage']['version_column_length']);
$storageConfiguration->setExecutedAtColumnName($config['table_storage']['executed_at_column_name']);
$storageConfiguration->setExecutionTimeColumnName($config['table_storage']['execution_time_column_name']);

$configuration->setMetadataStorageConfiguration($storageConfiguration);

$container = require_once __DIR__ . '/../config/container.php';

$dependencyFactory = DependencyFactory::fromConnection(
    new ExistingConfiguration($configuration),
    new ExistingConnection($connection)
);

$dependencyFactory->setService(Psr\Container\ContainerInterface::class, $container);

$cli = new Application('Doctrine Migrations');
$cli->setCatchExceptions(true);

$cli->addCommands([
    new Command\DumpSchemaCommand($dependencyFactory),
    new Command\ExecuteCommand($dependencyFactory),
    new Command\GenerateCommand($dependencyFactory),
    new Command\LatestCommand($dependencyFactory),
    new Command\ListCommand($dependencyFactory),
    new Command\MigrateCommand($dependencyFactory),
    new Command\RollupCommand($dependencyFactory),
    new Command\StatusCommand($dependencyFactory),
    new Command\SyncMetadataCommand($dependencyFactory),
    new Command\VersionCommand($dependencyFactory),
]);

$cli->run();
