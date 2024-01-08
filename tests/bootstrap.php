<?php declare(strict_types=1);

putenv('APP_ENV=testing');
system('rm ' . dirname(__FILE__) . '/../database/database.sqlite');
system('php ' . dirname(__FILE__) . '/../bin/migrations.php migrations:sync-metadata-storage');
system('php ' . dirname(__FILE__) . '/../bin/migrations.php migrations:migrate current+8 --no-interaction');
