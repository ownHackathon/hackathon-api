<?php declare(strict_types=1);

namespace ownHackathon;

use Laminas\ConfigAggregator\ConfigAggregator;
use ownHackathon\Core\Mailing;
use ownHackathon\Core\Shared;
use ownHackathon\Core\Token;

class ConfigProvider
{
    public function __invoke(): array
    {
        $aggregator = new ConfigAggregator([
            App\ConfigProvider::class,
            Core\ConfigProvider::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
