<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared\Domain\Token;

use ownHackathon\Core\Shared\Utils\CollectionInterface;
use ownHackathon\Core\Token\Domain\Token;

/**
 * @method Token offsetGet(mixed $offset)
 * @method Token current()
 * @method Token first()
 * @method Token last()
 */
interface TokenCollectionInterface extends CollectionInterface
{
}
