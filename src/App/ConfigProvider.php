<?php declare(strict_types=1);

namespace ownHackathon\App;

use Laminas\ConfigAggregator\ConfigAggregator;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            \ownHackathon\App\Account\Identity\ConfigProvider::class,
            Shared\ConfigProvider::class,
            Workspace\ConfigProvider::class,
            Event\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
