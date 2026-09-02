<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Domain;

use InvalidArgumentException;
use ownHackathon\Core\SharedKernel\Utils\Collection;

final class EventCollection extends Collection implements EventCollectionInterface
{
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof EventInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    $value::class,
                    EventInterface::class,
                ),
            );
        }
        parent::offsetSet($offset, $value);
    }
}
