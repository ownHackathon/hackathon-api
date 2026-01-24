<?php declare(strict_types=1);

namespace ownHackathon;

use Laminas\ConfigAggregator\ConfigAggregator;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            \Exdrals\Account\ConfigProvider::class,
            \Exdrals\Mailing\ConfigProvider::class,
            \ownHackathon\Workspace\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
