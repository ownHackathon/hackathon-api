<?php declare(strict_types=1);

namespace ownHackathon\Core;

use Laminas\ConfigAggregator\ConfigAggregator;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            Persistence\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
