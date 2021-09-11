<?php declare(strict_types=1);

$aggregator = require CONFIG_DIR . 'providers.php';

return $aggregator->getMergedConfig();
