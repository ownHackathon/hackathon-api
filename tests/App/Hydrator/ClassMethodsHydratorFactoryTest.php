<?php declare(strict_types=1);

namespace App\Hydrator;

use App\Test\Mock\MockContainer;
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
