<?php declare(strict_types=1);

namespace Core;

use Laminas\ConfigAggregator\ConfigAggregator;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            Http\ConfigProvider::class,
            Persistence\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
