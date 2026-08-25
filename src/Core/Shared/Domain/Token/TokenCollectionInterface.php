<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Domain\Token;

use Exdrals\Core\Shared\Utils\CollectionInterface;
use Exdrals\Core\Token\Domain\Token;

/**
 * @method Token offsetGet(mixed $offset)
 * @method Token current()
 * @method Token first()
 * @method Token last()
 */
interface TokenCollectionInterface extends CollectionInterface
{
}
