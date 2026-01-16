<?php declare(strict_types=1);

namespace Test\Unit\Core\Hydrator;

use Core\Hydrator\DateTimeFormatterStrategyFactory;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\MockContainer;

class DateTimeFormatterStrategyFactoryTest extends TestCase
{
    public function testCanCreateDateTimeFormatterStrategy(): void
    {
        $dateTimeFormatterStrategy = (new DateTimeFormatterStrategyFactory())(new MockContainer());

        self::assertInstanceOf(DateTimeFormatterStrategy::class, $dateTimeFormatterStrategy);
    }
}
