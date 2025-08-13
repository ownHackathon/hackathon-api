<?php declare(strict_types=1);

if (getenv('APP_ENV') !== 'action') {
    putenv('APP_ENV=testing');
}
system('php ' . dirname(__FILE__) . '/../../bin/migrations.php migrations:sync-metadata-storage --quiet');
system('php ' . dirname(__FILE__) . '/../../bin/migrations.php migrations:migrate --no-interaction --quiet');
// phpcs:ignore
system('php ' . dirname(__FILE__) . '/../../bin/migrations.php migrations:migrate Migrations\\\Version20231103223745_CreateAccountTable --no-interaction --quiet');
system('php ' . dirname(__FILE__) . '/../../bin/migrations.php migrations:migrate --no-interaction --quiet');
