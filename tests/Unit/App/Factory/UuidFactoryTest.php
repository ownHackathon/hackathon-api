<?php declare(strict_types=1);

namespace Test\Unit\App\Factory;

use App\Factory\UuidFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Test\Unit\Mock\MockContainer;

class UuidFactoryTest extends TestCase
{
    public function testCanCreateUuidInstanze(): void
    {
        $container = new MockContainer();

        $uuid = (new UuidFactory())($container);

        self::assertInstanceOf(UuidInterface::class, $uuid);
    }
}
