<?php declare(strict_types=1);

namespace App\Account;

use Laminas\ConfigAggregator\ConfigAggregator;
use App\Account;

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
