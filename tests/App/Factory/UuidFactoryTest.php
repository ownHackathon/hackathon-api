<?php declare(strict_types=1);

namespace App\Test\Factory;

use App\Factory\UuidFactory;
use App\Test\Mock\MockContainer;
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
