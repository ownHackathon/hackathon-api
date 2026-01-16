<?php declare(strict_types=1);

namespace Core\Factory;

use Core\Utils\UuidFactoryInterface;

class UuidFactory
{
    public function __invoke(): UuidFactoryInterface
    {
        return new \Core\Utils\UuidFactory();
    }
}
