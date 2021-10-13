<?php
declare(strict_types=1);

namespace App\Hydrator;

use Laminas\Hydrator\ClassMethodsHydrator;

class ClassMethodsHydratorFactory
{
    public function __invoke()
    {
        return new ClassMethodsHydrator(false, true);
    }
}