<?php declare(strict_types=1);

namespace ownHackathon\Account;

use Laminas\ConfigAggregator\ConfigAggregator;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            \Exdrals\Identity\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
