<?php declare(strict_types=1);

namespace App\Test\Hydrator;

use App\Hydrator\ReflectionHydrator;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ReflectionHydratorTest extends TestCase
{
    private ReflectionHydrator $hydrator;

    public function setUp(): void
    {
        $this->hydrator = new ReflectionHydrator();
    }

    public function testCanNotHydrate(): void
    {
        $hydrate = $this->hydrator->hydrate(false, new User());

        $this->assertNull($hydrate);
    }

    public function testCanHydrate(): void
    {
        $data = [
            'id' => 1,
        ];

        $hydrate = $this->hydrator->hydrate($data, new User());

        $this->assertInstanceOf(User::class, $hydrate);
    }

    public function testCanHydrateListWithoutData(): void
    {
        $hydrate = $this->hydrator->hydrateList([], User::class);

        $this->assertIsArray($hydrate);
        $this->assertSame(0, count($hydrate));
    }

    public function testCanHydrateListWithData(): void
    {
        $hydrate = $this->hydrator->hydrateList([0 => ['id' => 1]], User::class);

        $this->assertIsArray($hydrate);
        $this->assertArrayHasKey(0, $hydrate);
        $this->assertInstanceOf(User::class, $hydrate[0]);
    }
}
