<?php declare(strict_types=1);

namespace Test\Unit\App\Hydrator;

use App\Hydrator\DateTimeFormatterStrategyFactory;
use Test\Unit\App\Mock\MockContainer;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use PHPUnit\Framework\TestCase;

class DateTimeFormatterStrategyFactoryTest extends TestCase
{
    public function testCanCreateDateTimeFormatterStrategy(): void
    {
        $dateTimeFormatterStrategy = (new DateTimeFormatterStrategyFactory())(new MockContainer());

        $this->assertInstanceOf(DateTimeFormatterStrategy::class, $dateTimeFormatterStrategy);
    }
}
