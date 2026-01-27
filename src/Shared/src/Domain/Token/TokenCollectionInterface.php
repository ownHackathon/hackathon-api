<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Token;

use Exdrals\Identity\Domain\Token;
use Exdrals\Shared\Utils\CollectionInterface;

/**
 * @method Token offsetGet(mixed $offset)
 * @method Token current()
 * @method Token first()
 * @method Token last()
 */
interface TokenCollectionInterface extends CollectionInterface
{
}
