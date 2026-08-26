<?php declare(strict_types=1);

namespace ownHackathon\App\Shared\Domain\Event;

use ownHackathon\Core\Shared\Utils\CollectionInterface;

/**
 * @method EventInterface offsetGet(mixed $offset)
 * @method EventInterface current()
 * @method EventInterface first()
 * @method EventInterface last()
 */
interface EventCollectionInterface extends CollectionInterface
{
}
