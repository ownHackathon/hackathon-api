<?php declare(strict_types=1);

namespace App\Event\Domain;

use Core\SharedKernel\Utils\Collection;
use InvalidArgumentException;

use function get_debug_type;
use function sprintf;

final class EventCollection extends Collection implements EventCollectionInterface
{
    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof EventInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_debug_type($value),
                    EventInterface::class,
                ),
            );
        }
        parent::offsetSet($offset, $value);
    }
}
