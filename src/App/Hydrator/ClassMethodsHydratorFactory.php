<?php declare(strict_types=1);

namespace App\Hydrator;

use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Container\ContainerInterface;

readonly class ClassMethodsHydratorFactory
{
    public function __invoke(ContainerInterface $container): ClassMethodsHydrator
    {
        return new ClassMethodsHydrator(false, true);
    }
}
