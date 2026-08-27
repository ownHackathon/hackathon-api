<?php declare(strict_types=1);

namespace ownHackathon\App\Token\Domain;

use ownHackathon\Core\SharedKernel\Utils\CollectionInterface;

/**
 * @method Token offsetGet(mixed $offset)
 * @method Token current()
 * @method Token first()
 * @method Token last()
 */
interface TokenCollectionInterface extends CollectionInterface
{
}
