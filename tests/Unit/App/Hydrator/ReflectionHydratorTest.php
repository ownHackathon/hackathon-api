<?php declare(strict_types=1);

namespace Test\Unit\App\Hydrator;

use App\Entity\User;
use App\Hydrator\ReflectionHydrator;
use PHPUnit\Framework\TestCase;
use Test\Data\Entity\UserTestEntity;

class ReflectionHydratorTest extends TestCase
{
    private ReflectionHydrator $hydrator;

    public function setUp(): void
    {
        $this->hydrator = new ReflectionHydrator();
    }

    public function testCanNotHydrate(): void
    {
        $hydrate = $this->hydrator->hydrate(false, User::class);

        self::assertNull($hydrate);
    }

    public function testCanHydrate(): void
    {
        $hydrate = $this->hydrator->hydrate(UserTestEntity::getDefaultUserValue(), User::class);

        self::assertInstanceOf(User::class, $hydrate);
    }

    public function testCanHydrateListWithoutData(): void
    {
        $hydrate = $this->hydrator->hydrateList([], User::class);

        self::assertIsArray($hydrate);
        self::assertSame(0, count($hydrate));
    }

    public function testCanHydrateListWithData(): void
    {
        $hydrate = $this->hydrator->hydrateList([0 => UserTestEntity::getDefaultUserValue()], User::class);

        self::assertIsArray($hydrate);
        self::assertArrayHasKey(0, $hydrate);
        self::assertInstanceOf(User::class, $hydrate[0]);
    }
}
