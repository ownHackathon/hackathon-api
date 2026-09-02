<?php

declare(strict_types=1);

chdir(__DIR__ . '/../');

require 'vendor/autoload.php';

use ownHackathon\Core\Observability\LogsPruner;

$config = include 'config/config.php';

/** @var array{logger: array{path?: string, retention_days?: int}} $config */
$logPath = $config['logger']['path'] ?? null;
$retentionDays = $config['logger']['retention_days'] ?? 30;

if (! is_string($logPath) || $logPath === '') {
    echo "No logger path configured" . PHP_EOL;
    exit(1);
}

$removed = LogsPruner::prune($logPath, $retentionDays);

printf(
    "Removed %d outdated log director%s%s",
    $removed,
    $removed === 1 ? 'y' : 'ies',
    PHP_EOL
);
exit(0);
