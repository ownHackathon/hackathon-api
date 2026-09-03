<?php declare(strict_types=1);

namespace App\Event\Domain;

use Core\SharedKernel\Utils\CollectionInterface;

/**
 * @method EventInterface offsetGet(mixed $offset)
 * @method EventInterface current()
 * @method EventInterface first()
 * @method EventInterface last()
 */
interface EventCollectionInterface extends CollectionInterface
{
}
