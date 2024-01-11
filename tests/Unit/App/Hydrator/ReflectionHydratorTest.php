<?php declare(strict_types=1);

namespace Test\Unit\App\Hydrator;

use App\Entity\User;
use App\Hydrator\ReflectionHydrator;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\TestConstants;

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

        self::assertNull($hydrate);
    }

    public function testCanHydrate(): void
    {
        $data = [
            'id' => TestConstants::USER_ID,
        ];

        $hydrate = $this->hydrator->hydrate($data, new User());

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
        $hydrate = $this->hydrator->hydrateList([0 => ['id' => TestConstants::USER_ID]], User::class);

        self::assertIsArray($hydrate);
        self::assertArrayHasKey(0, $hydrate);
        self::assertInstanceOf(User::class, $hydrate[0]);
    }
}
