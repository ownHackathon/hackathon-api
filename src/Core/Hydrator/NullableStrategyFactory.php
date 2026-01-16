<?php declare(strict_types=1);

namespace Core\Hydrator;

use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Psr\Container\ContainerInterface;

readonly class NullableStrategyFactory
{
    public function __invoke(ContainerInterface $container): NullableStrategy
    {
        $dateTimeFormatterStrategy = $container->get(DateTimeFormatterStrategy::class);

        return new NullableStrategy($dateTimeFormatterStrategy);
    }
}
