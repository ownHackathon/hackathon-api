<?php declare(strict_types=1);

namespace App\Middleware;

use App\Hydrator\ClassMethodsHydratorFactory;
use Laminas\Hydrator\ClassMethodsHydrator;

class TopicSubmitMiddlewareTest extends AbstractMiddlewareTest
{
    private ClassMethodsHydrator $hydrator;

    protected function setUp(): void
    {
        $this->hydrator = (new ClassMethodsHydratorFactory())();
        parent::setUp();
    }
}
