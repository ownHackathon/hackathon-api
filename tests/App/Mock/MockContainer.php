<?php declare(strict_types=1);

namespace App\Test\Mock;

use Psr\Container\ContainerInterface;

class MockContainer implements ContainerInterface
{
    public function __construct(
        private array $container = [],
    ) {
    }

    public function get(string $id): mixed
    {
        if ($this->has($id)) {
            return $this->container[$id];
        }

        return null;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->container);
    }

    public function add(string $id, mixed $value): void
    {
        $this->container[$id] = $value;
    }
}
