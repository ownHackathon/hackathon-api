<?php declare(strict_types=1);

namespace ownHackathon\App\Account;

use Laminas\ConfigAggregator\ConfigAggregator;
use ownHackathon\App\Account;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            Account\Identity\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
