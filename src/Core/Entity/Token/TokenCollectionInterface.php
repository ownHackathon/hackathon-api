<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity\Token;

use ownHackathon\App\Entity\Token\Token;
use ownHackathon\Core\Utils\CollectionInterface;

/**
 * @method Token offsetGet(mixed $offset)
 * @method Token current()
 * @method Token first()
 * @method Token last()
 */
interface TokenCollectionInterface extends CollectionInterface
{
}
