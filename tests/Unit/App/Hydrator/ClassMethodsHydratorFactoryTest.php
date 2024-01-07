<?php declare(strict_types=1);

namespace Test\Unit\App\Hydrator;

use App\Hydrator\ClassMethodsHydratorFactory;
use Test\Unit\App\Mock\MockContainer;
use Laminas\Hydrator\ClassMethodsHydrator;
use PHPUnit\Framework\TestCase;

class ClassMethodsHydratorFactoryTest extends TestCase
{
    public function testCanCreateClassMethodsHydrator(): void
    {
        $classMethodsHydrator = (new ClassMethodsHydratorFactory())(new MockContainer());

        $this->assertInstanceOf(ClassMethodsHydrator::class, $classMethodsHydrator);
    }
}
