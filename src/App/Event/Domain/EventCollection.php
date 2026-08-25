<?php declare(strict_types=1);

namespace ownHackathon\Event\Domain;

use Exdrals\Core\Shared\Utils\Collection;
use InvalidArgumentException;
use ownHackathon\Shared\Domain\Event\EventCollectionInterface;
use ownHackathon\Shared\Domain\Event\EventInterface;

class EventCollection extends Collection implements EventCollectionInterface
{
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof EventInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_class($value),
                    EventInterface::class
                )
            );
        }
        parent::offsetSet($offset, $value);
    }
}
