<?php declare(strict_types=1);

namespace Exdrals\Account;

use Laminas\ConfigAggregator\ConfigAggregator;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            Identity\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
