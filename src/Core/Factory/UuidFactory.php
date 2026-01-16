<?php declare(strict_types=1);

namespace ownHackathon\Core\Factory;

use ownHackathon\Core\Utils\UuidFactoryInterface;

class UuidFactory
{
    public function __invoke(): UuidFactoryInterface
    {
        return new \ownHackathon\Core\Utils\UuidFactory();
    }
}
