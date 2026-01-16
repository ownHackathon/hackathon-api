<?php declare(strict_types=1);

namespace Core\Entity\Token;

use App\Entity\Token\Token;
use Core\Utils\CollectionInterface;

/**
 * @method Token offsetGet(mixed $offset)
 * @method Token current()
 * @method Token first()
 * @method Token last()
 */
interface TokenCollectionInterface extends CollectionInterface
{
}
