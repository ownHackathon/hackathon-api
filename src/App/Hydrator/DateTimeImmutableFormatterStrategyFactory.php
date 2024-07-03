<?php declare(strict_types=1);

namespace App\Hydrator;

use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\DateTimeImmutableFormatterStrategy;
use Psr\Container\ContainerInterface;

readonly class DateTimeImmutableFormatterStrategyFactory
{
    public function __invoke(ContainerInterface $container): DateTimeImmutableFormatterStrategy
    {
        return new DateTimeImmutableFormatterStrategy(new DateTimeFormatterStrategy('Y-m-d H:i:s'));
    }
}
