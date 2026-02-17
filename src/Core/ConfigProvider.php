<?php declare(strict_types=1);

namespace Exdrals\Core;

use Laminas\ConfigAggregator\ConfigAggregator;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            Mailing\ConfigProvider::class,
            Shared\ConfigProvider::class,
            Token\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
