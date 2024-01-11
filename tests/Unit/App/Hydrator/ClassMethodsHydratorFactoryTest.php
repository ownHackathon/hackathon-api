<?php declare(strict_types=1);

namespace Test\Unit\App\Hydrator;

use App\Hydrator\ClassMethodsHydratorFactory;
use Laminas\Hydrator\ClassMethodsHydrator;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\MockContainer;

class ClassMethodsHydratorFactoryTest extends TestCase
{
    public function testCanCreateClassMethodsHydrator(): void
    {
        $classMethodsHydrator = (new ClassMethodsHydratorFactory())(new MockContainer());

        self::assertInstanceOf(ClassMethodsHydrator::class, $classMethodsHydrator);
    }
}
