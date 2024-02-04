<?php declare(strict_types=1);

namespace App\Hydrator;

use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

readonly class DateTimeFormatterStrategyFactory
{
    public function __invoke(ContainerInterface $container): DateTimeFormatterStrategy
    {
        return new DateTimeFormatterStrategy('Y-m-d H:i:s');
    }
}
