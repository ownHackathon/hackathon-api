<?php declare(strict_types=1);

namespace Core\SharedKernel\Utils;

use Closure;
use Core\SharedKernel\Domain\Exception\UndefinedOffsetException;

use function array_filter;
use function array_pop;
use function array_shift;
use function count;
use function is_null;
use function sprintf;
use function var_export;

abstract class Collection implements CollectionInterface
{
    /**
     * @param array<Collectible> $collection
     */
    protected array $collection = [];
    private int $position = 0;

    #[\Override]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->collection[$offset]);
    }

    /**
     * @throws UndefinedOffsetException
     */
    #[\Override]
    public function offsetGet(mixed $offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            throw new UndefinedOffsetException(
                sprintf('Undefined offset: %s in Collection %s on Line %s', var_export($offset, true), __FILE__, __LINE__),
            );
        }

        return $this->collection[$offset];
    }

    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        is_null($offset)
            ? $this->collection[] = $value
            : $this->collection[$offset] = $value;
    }

    #[\Override]
    public function offsetUnset(mixed $offset): void
    {
        unset($this->collection[$offset]);
    }

    #[\Override]
    public function current(): mixed
    {
        return $this->collection[$this->position];
    }

    #[\Override]
    public function next(): void
    {
        $this->position++;
    }

    #[\Override]
    public function key(): int
    {
        return $this->position;
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->offsetExists($this->position);
    }

    #[\Override]
    public function rewind(): void
    {
        $this->position = 0;
    }

    #[\Override]
    public function count(): int
    {
        return count($this->collection);
    }

    public function first(): mixed
    {
        $collection = $this->collection;
        return array_shift($collection);
    }

    public function last(): mixed
    {
        $collection = $this->collection;
        return array_pop($collection);
    }

    /**
     * @return array<Collectible>
     */
    public function filter(Closure $function): array
    {
        return array_filter($this->collection, $function);
    }

    public function getElements(): array
    {
        return $this->collection;
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->getElements();
    }
}
