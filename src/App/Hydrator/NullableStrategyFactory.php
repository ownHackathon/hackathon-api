<?php declare(strict_types=1);

namespace App\Hydrator;

use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Psr\Container\ContainerInterface;

class NullableStrategyFactory
{
    public function __invoke(ContainerInterface $container): NullableStrategy
    {
        $dateTimeFormatterStrategy = $container->get(DateTimeFormatterStrategy::class);

        return new NullableStrategy($dateTimeFormatterStrategy);
    }
}
