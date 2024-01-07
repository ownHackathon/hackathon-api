<?php declare(strict_types=1);

namespace Test\Unit\App\Factory;

use App\Factory\UuidFactory;
use Test\Unit\App\Mock\MockContainer;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class UuidFactoryTest extends TestCase
{
    public function testCanCreateUuidInstanze(): void
    {
        $container = new MockContainer();

        $uuid = (new UuidFactory())($container);

        $this->assertInstanceOf(UuidInterface::class, $uuid);
    }
}
