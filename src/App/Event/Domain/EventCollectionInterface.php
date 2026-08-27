<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Domain;

use ownHackathon\Core\SharedKernel\Utils\CollectionInterface;

/**
 * @method EventInterface offsetGet(mixed $offset)
 * @method EventInterface current()
 * @method EventInterface first()
 * @method EventInterface last()
 */
interface EventCollectionInterface extends CollectionInterface
{
}
